<?php

namespace App\Http\Admin\Controllers\Users;

use App\Domain\User\Entities\UserRequest;
use App\Domain\User\Enums\UserRequestStatus;
use App\Domain\User\Repositories\UserRequestRepository;
use App\Domain\User\Services\UserRequestService;
use App\Http\Admin\Controllers\ResourceController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;


class UserRequestController extends ResourceController
{
    protected bool $supportsShowMethod = true;

    public function __construct(UserRequestRepository $repository, UserRequestService $service)
    {
        parent::__construct($repository, $service);
    }

    protected function rules($model = null): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|max:255',
            'text' => 'required|string|max:1024',
            'status' => 'required|string|max:255',
        ];
    }

    protected function resourceClass(): string
    {
        return UserRequest::class;
    }

    public function show(Request $request): View
    {
        $model = $this->findModel($request);
        $model->status = UserRequestStatus::READ;
        $model->saveOrFail();
        return $this->render('show', model: $model);
    }
}
