<?php

namespace App\Jobs;

use App\Models\Session;
use App\Models\Step;
use App\Models\StepHistory;
use App\Services\FileService;
use App\Services\StepStatusService;
use DB;
use Exception;
use Http;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Storage;
use Throwable;

class Filter3dmJob implements ShouldQueue
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

    public function middleware()
    {
        return [(new WithoutOverlapping('3dm'))->releaseAfter(60)];
    }

    /**
     * Execute the job.
     *
     * @param  StepStatusService  $stepStatusService
     * @param  FileService  $fileService
     * @return void
     */
    public function handle(
        StepStatusService $stepStatusService,
        FileService $fileService
    ): void {
        DB::transaction(function () use ($stepStatusService, $fileService) {
            $file = $this->step->file;

            $isRight = $this->step->step <= 8;
            $stepRange = $isRight ? range(1, 8) : range(9, 16);

            $this->rollback($stepStatusService, $stepRange);

            $previousStatus = $stepStatusService->prev('3dm');

            $steps = $this->session->steps()
                ->sharedLock()
                ->whereIn('step', $stepRange)
                ->where('status', $previousStatus['status'])
                ->get();

            $stream = Storage::disk($file->disk)->readStream($file->path);

            $response = Http::attach('image_file', $stream, $this->session->name.'.zip')
                ->retry(1)
                ->post(config('services.filters.3dm'), [
                    'mirr' => $isRight ? 'mirr1' : 'mirr2',
                ]);

            if ($response->failed()) {
                logger()->warning('3DM Response:'.$response->body());
                return;
            }

            if ($response->header('Content-Type') == 'application/json') {
                $json = json_decode($response->body());
                throw new Exception($json->error);
            }

            $resultFile = $fileService->uploadResponse($response);

            Step::query()
                ->whereIn('id', $steps->pluck('id'))
                ->each(function (Step $step) use ($resultFile) {
                    $step->update([
                        'file_id' => $resultFile->id,
                        'status' => '3dm',
                        'exception' => null,
                    ]);
                });

            $nextStatus = $stepStatusService->next('3dm');
            if (!is_null($nextStatus['job'])) {
                dispatch(new $nextStatus['job']($this->session, $this->step))->afterCommit();
            }
        }, 5);
    }

    public function failed(Throwable $exception)
    {
        $this->step->exception = $exception->getMessage();
        $this->step->save();
    }

    protected function rollback(StepStatusService $stepStatusService, array $stepRange)
    {
        $inNextStepIds = $this->session->steps()
            ->sharedLock()
            ->whereIn('step', $stepRange)
            ->whereIn('status', $stepStatusService->allNextStatus('centr'))
            ->pluck('id');

        if ($inNextStepIds->isNotEmpty()) {
            $lastStatuses = StepHistory::query()
                ->select([
                    'step_id',
                    'to_status',
                    DB::raw('MAX(result_id) as result_id')
                ])
                ->whereIn('step_id', $inNextStepIds)
                ->where('to_status', 'centr')
                ->groupBy(['step_id', 'to_status'])
                ->get();

            foreach ($lastStatuses as $lastStatus) {
                Step::query()
                    ->where('id', $lastStatus->step_id)
                    ->update([
                        'file_id' => $lastStatus->result_id,
                        'status' => $lastStatus->to_status,
                    ]);
            }
        }
    }
}
