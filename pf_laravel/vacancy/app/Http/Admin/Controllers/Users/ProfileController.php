<?php

namespace App\Http\Admin\Controllers\Users;

use App\Domain\Application\Language\Repositories\LanguageRepository;
use App\Domain\User\Services\UserManageService;
use App\Http\Admin\Requests\Profile\ProfileUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController
{
    public function __construct(
        private UserManageService $service,
        private LanguageRepository $languageRepository
    )
    {
    }

    public function edit(Request $request): View
    {
        return view('admin.profile.update', [
            'user'      => $request->user(),
            'languages' => $this->languageRepository->allActive()->keyBy('id')->map->title,
        ]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        if (DB::transaction(fn() => $this->service->update($request->user(), $request->validated()))) {
            $request->session()->flash('success', __('admin.messages.crud.updated'));
        } else {
            $request->session()->flash('error', __('admin.messages.crud.error'));
        }

        return redirect(route('admin.profile.edit'));
    }
}
