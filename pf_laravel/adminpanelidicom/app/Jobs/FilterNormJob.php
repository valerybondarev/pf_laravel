<?php

namespace App\Jobs;

use App\Helpers\FileHelper;
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

class FilterNormJob implements ShouldQueue
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
        return [(new WithoutOverlapping('norm'))->releaseAfter(60)];
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

        $response = Http::attach('image_file', $stream, $this->step->file->original)
            ->post(config('services.filters.norm'), $this->step->options);

        if ($response->failed()) {
            throw new Exception('Response error:'.$response->status());
        }

        $resultFile = $fileService->uploadResponse($response);

        if ($this->step->step == 0) {
            $this->step->update([
                'file_id' => $resultFile->id,
                'status' => 'done',
                'exception' => null,
            ]);
            return;
        }

        $this->step->update([
            'file_id' => $resultFile->id,
            'status' => 'norm',
            'exception' => null,
        ]);

        $nextStatus = $stepStatusService->next($this->step->status);
        if (!is_null($nextStatus['job'])) {
            dispatch(new $nextStatus['job']($this->session, $this->step))->afterCommit();
        }
    }

    public function failed(Throwable $exception)
    {
        $this->step->exception = $exception->getMessage();
        $this->step->save();
    }
}
