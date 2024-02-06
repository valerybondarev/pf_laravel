<?php

namespace App\Http\Admin\Controllers\Users;

use App\Domain\Application\Language\Repositories\LanguageRepository;
use App\Domain\User\Entities\User;
use App\Domain\User\Enums\UserStatusEnum;
use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\Services\UserManageService;
use App\Http\Admin\Controllers\ResourceController;
use App\Rules\Date;
use Illuminate\Validation\Rule;


class UserController extends ResourceController
{
    public function __construct(
        UserRepository $repository,
        UserManageService $service,
        private LanguageRepository $languageRepository,
    )
    {
        parent::__construct($repository, $service);
    }

    protected function resourceClass(): string
    {
        return User::class;
    }

    protected function viewParameters(): array
    {
        return [
            'languages' => $this->languageRepository->allActive()->keyBy('id')->map->title,
        ];
    }

    protected function rules($model = null): array
    {
        return [
            'avatarId'     => 'nullable|exists:images,id',
            'languageId'   => 'nullable|exists:languages,id|prohibits:languageCode',
            'languageCode' => 'nullable|exists:languages,code|prohibits:languageId',

            'lastName'   => 'nullable|string',
            'firstName'  => 'nullable|string',
            'middleName' => 'nullable|string',
            'position'   => 'nullable|string',
            'status'     => ['nullable', Rule::in(UserStatusEnum::keys())],
            'bornAt'     => ['nullable', new Date()],

            'email'    => [
                'nullable',
                'required_without_all:username,phone',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($model),
            ],

            'username' => [
                'nullable',
                'required_without_all:email,phone',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($model),
            ],

            'phone' => [
                'nullable',
                'required_without_all:email',
                Rule::phone()->mobile()->country('ru'),
                Rule::unique('users', 'phone_e164')->ignore($model),
            ],

            'password' => [
                Rule::requiredIf(fn() => $model === null),
                'string'
            ],

            'changePassword' => 'nullable|boolean',
            'newPassword'    => 'nullable|required_if:changePassword,1|string',
        ];
    }
}
