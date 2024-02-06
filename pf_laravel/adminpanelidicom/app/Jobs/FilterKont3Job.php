<?php

namespace App\Jobs;

use App\Models\Session;
use App\Models\Step;
use App\Services\FileService;
use App\Services\StepStatusService;
use Exception;
use Http;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Storage;

class FilterKont3Job implements ShouldQueue
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
        return [(new WithoutOverlapping('kont3'))->releaseAfter(60)];
    }

    /**
     * Execute the job.
     *
     * @param  StepStatusService  $stepStatusService
     * @param  FileService  $fileService
     * @return void
     * @throws Exception
     */
    public function handle(
        StepStatusService $stepStatusService,
        FileService $fileService
    ): void {
        $file = $this->step->file;

        $stream = Storage::disk($file->disk)->readStream($file->path);

        $response = Http::attach('file', $stream, $file->original)
            ->retry(1)
            ->timeout(120)
            ->post(config('services.filters.kont3'));

        if ($response->failed()) {
            logger()->warning('KONT3 Response:'.$response->body());
            return;
        }

        $resultFile = $fileService->uploadResponse($response);

        $this->step->update([
            'file_id' => $resultFile->id,
            'status' => 'kont3',
            'exception' => null,
        ]);

        $nextStatus = $stepStatusService->next($this->step->status);
        if (!is_null($nextStatus['job'])) {
            dispatch(new $nextStatus['job']($this->session, $this->step))->afterCommit();
        }
    }
}
