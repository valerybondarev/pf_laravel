<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SessionChangeRequest;
use App\Http\Requests\Admin\SessionIndexRequest;
use App\Models\Session;
use App\Models\Step;
use App\Services\FileService;
use App\Services\StepStatusService;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SessionController extends Controller
{
    public function index(SessionIndexRequest $request)
    {
        $sessions = Session::query()
            ->when($request->has('filter.query'), function (Builder $builder) use ($request) {
                $builder->where('name', 'like', '%'.$request->input('filter.query').'%')
                    ->orWhere('device_id', 'like', '%'.$request->input('filter.query').'%')
                    ->orWhere('nickname', 'like', '%'.$request->input('filter.query').'%');
            })
            ->latest()
            ->paginate();

        return view('admin.sessions.index', [
            'sessions' => $sessions,
            'filter' => $request->input('filter')
        ]);
    }

    public function show(StepStatusService $stepStatusService, Session $session)
    {
        $session->load([
            'steps' => function (HasMany $query) {
                $query->orderBy('step');
            },
            'steps.file',
            'steps.histories' => function (HasMany $query) {
                $query->orderByDesc('id');
            },
            'steps.histories.source',
            'steps.histories.result',
        ]);

        $data = [];
        foreach ($session->steps as $step) {
            foreach ($step->histories as $history) {
                if (isset($data[$step->step][$history->to_status])) {
                    continue;
                }
                if ($history->from_status == StepStatusService::DEFAULT_STATUS) {
                    $data[$step->step][$history->from_status] = [
                        'result' => $history->source->toArray(),
                    ];
                }
                $data[$step->step][$history->to_status] = [
                    'result' => $history->result->toArray(),
                ];
            }

        }

        $statuses = array_merge(
            [StepStatusService::DEFAULT_STATUS],
            $stepStatusService->allNextStatus(StepStatusService::DEFAULT_STATUS)
        );

        return view('admin.sessions.show', [
            'session' => $session,
            'statuses' => $statuses,
            'steps' => $session->steps,
            'data' => $data,
        ]);
    }

    public function change(
        StepStatusService $stepStatusService,
        FileService $fileService,
        SessionChangeRequest $request,
        Session $session
    ) {
        DB::transaction(function () use ($stepStatusService, $fileService, $request, $session) {
            $file = $fileService->upload($request->file('file'));

            /** @var Step $step */
            $step = $session->steps()->where('step', $request->input('step'))->first();
            $step->update([
                'file_id' => $file->id,
                'status' => $request->input('status'),
            ]);


            $status = $stepStatusService->next($request->input('status'));

            dispatch(new $status['job']($session, $step))->afterCommit();
        }, 5);

        return redirect()
            ->route('admin.sessions.show', [$session->id])
            ->with(
                'success',
                __('Upload to step :step and status :status successful', [
                    'step' => $request->input('step'),
                    'status' => $request->input('status'),
                ])
            );
    }
}
