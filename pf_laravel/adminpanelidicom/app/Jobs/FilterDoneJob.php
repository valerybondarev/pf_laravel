<?php

namespace App\Jobs;

use App\Models\Session;
use App\Models\Step;
use App\Services\StepStatusService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FilterDoneJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected Session $session,
        protected Step $step,
    ) {
        $this->onQueue('filters');
    }

    public function handle(
        StepStatusService $stepStatusService,
    ) {
        $isRight = $this->step->step <= 8;
        $stepRange = $isRight ? range(1, 8) : range(9, 16);

        $previousStatus = $stepStatusService->prev('done');

        $steps = $this->session->steps()
            ->sharedLock()
            ->whereIn('step', $stepRange)
            ->where('status', $previousStatus['status'])
            ->get();

        Step::query()
            ->whereIn('id', $steps->pluck('id'))
            ->each(function (Step $step) {
                $step->update([
                    'status' => 'done',
                    'exception' => null,
                ]);
            });
    }

}
