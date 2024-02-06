<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\SessionUploadRequest;
use App\Models\Session;
use App\Models\Step;
use App\Services\FileService;
use App\Services\StepStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    public function upload(
        Request $fileRequest,
        StepStatusService $stepStatusService,
        FileService $fileService,
    ) {
        $request = new SessionUploadRequest();
        DB::transaction(function () use ($fileRequest, $stepStatusService, $request, $fileService) {
            /** @var Session $session */
            $session = Session::query()->firstOrCreate([
                //'name' => $request->input('session'),
                'name' => 'test_'.mt_rand(1,1000),
                'device_id' => $request->input('device_id'),
                //'nickname' => $request->input('nickname'),
                'nickname' => 'test_' . mt_rand(1,1000),
            ]);

            $file = $fileService->upload($fileRequest->file('userfile'));

            /** @var Step $step */
            $step = $session->steps()->updateOrCreate(
                [
                    'step' => 1,
                    //'step' => $request->input('step'),
                ],
                [
                    'options' => [
                        'ModelPhone' => $request->input('ModelPhone')
                    ],
                    'file_id' => $file->id,
                    'status' => $stepStatusService->get()['status'],
                ]
            );

            $status = $stepStatusService->next();

            dispatch(new $status['job']($session, $step))->afterCommit();
        }, 5);
        return redirect('admin/sessions');
    }

    public function status(Session $session)
    {
        $session->load([
            'steps',
            'steps.file'
        ]);

        $result = [
            'data' => [
                'id' => $session->name,
                'files' => [
                    'left' => null,
                    'right' => null,
                ],
                'images' => [],
            ]
        ];
        foreach ($session->steps as $step) {
            $result['data']['images']['image'.$step->step] = $step->status;
        }
        foreach (range(0, 16) as $idx) {
            /** @var Step $step */
            $step = $session->steps->where('step', $idx)->first();
            $result['data']['images']['image'.$idx] = $step?->status;
        }

        /** @var Step $right */
        $right = $session->steps->where('step', 1)->where('status', 'done')->first();
        /** @var Step $left */
        $left = $session->steps->where('step', 9)->where('status', 'done')->first();

        if ($right) {
            $result['data']['files']['right'] = URL::temporarySignedRoute('files.download', 3600, [$right->file->id]);
        }

        if ($left) {
            $result['data']['files']['left'] = URL::temporarySignedRoute('files.download', 3600, [$left->file->id]);
        }

        return response()->json($result);
    }
}
