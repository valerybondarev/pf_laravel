<?php
/**
 * Created date 17.07.2020
 *
 * @author Sergey Tyrgola <ts@goldcarrot.ru>
 */

namespace App\Domain\User\Services;

use App\Base\Interfaces\ManageServiceInterface;
use App\Domain\Application\Language\Repositories\LanguageRepository;
use App\Domain\User\Entities\User;
use App\Domain\User\Enums\UserStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManageService implements ManageServiceInterface
{
    public function __construct(
        protected LanguageRepository $languageRepository,
    )
    {
    }

    public function create(array $data): User
    {
        $user = new User();
        $user->status = UserStatusEnum::ACTIVE;
        $user->remember_token = Str::random(60);
        $user->password = Hash::make($password = Str::random(8));

        return $this->update($user, $data);
    }

    public function update(Model|User $model, array $data): User
    {
        $model->email = Arr::get($data, 'email', $model->email);
        $model->username = Arr::get($data, 'username', $model->username);
        $model->avatar_id = Arr::get($data, 'avatarId', $model->avatar_id);
        $model->language_id = Arr::get($data, 'languageId', $model->language_id);
        $model->last_name = Arr::get($data, 'lastName', $model->last_name);
        $model->first_name = Arr::get($data, 'firstName', $model->first_name);
        $model->middle_name = Arr::get($data, 'middleName', $model->middle_name);
        $model->status = Arr::get($data, 'status', $model->status);
        $model->born_at = Arr::get($data, 'bornAt', $model->born_at);

        $model->saveOrFail();

        Arr::has($data, 'password') && $this->updatePassword($model, Arr::get($data, 'password'));
        Arr::has($data, 'phone') && $this->updatePhone($model, Arr::get($data, 'phone'));
        Arr::has($data, 'languageCode') && $this->updateLanguage($model, Arr::get($data, 'languageCode'));

        if (Arr::get($data, 'changePassword') && ($newPassword = Arr::get($data, 'newPassword'))) {
            $this->updatePassword($model, $newPassword);
        }

        return $model;
    }

    public function destroy(Model|User $model): bool
    {
        return $model->delete();
    }

    private function updatePassword(Model|User $model, string $password): bool
    {
        $model->password = Hash::make($password);
        $model->password_changed_at = now();
        return $model->save();
    }

    private function updatePhone(Model|User $model, ?string $phone): bool
    {
        $model->phone = $phone;
        $model->phone_e164 = $model->phone?->formatE164();
        return $model->save();
    }

    private function updateLanguage(Model|User $model, ?string $languageCode): bool
    {
        $model->language()->dissociate();

        if ($language = $this->languageRepository->find(['code' => $languageCode])) {
            $model->language()->associate($language);
        }

        return $model->save();
    }
}
