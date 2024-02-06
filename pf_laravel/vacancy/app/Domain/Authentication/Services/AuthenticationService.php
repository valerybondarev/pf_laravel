<?php
/**
 * Created date 31.03.2021
 *
 * @author Sergey Tyrgola <ts@goldcarrot.ru>
 */

namespace App\Domain\Authentication\Services;


use App\Domain\Authentication\Interfaces\AuthenticationInterface;
use App\Domain\PhoneCode\Services\PhoneCodeService;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepository;
use Arr;
use Illuminate\Support\Facades\Hash;

abstract class AuthenticationService implements AuthenticationInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private PhoneCodeService $phoneCodeService,
    )
    {
    }

    protected function withEmail($email, $password): ?User
    {
        if ($email && $user = $this->userRepository->find(['email' => $email])) {
            return Hash::check($password, $user->password) ? $user : null;
        }
        return null;
    }

    protected function withUsername($username, $password): ?User
    {
        if ($username && $user = $this->userRepository->find(['username' => $username])) {
            return Hash::check($password, $user->password) ? $user : null;
        }
        return null;
    }

    protected function withPhone($phone, $password): ?User
    {
        if ($phone && $user = $this->userRepository->find(['phone' => $phone])) {
            return Hash::check($password, $user->password) ? $user : null;
        }
        return null;
    }

    protected function withPhoneCode($phone, $phoneCode): ?User
    {
        if ($phone && $user = $this->userRepository->find(['phone' => $phone])) {
            return $this->phoneCodeService->submit($phone, $phoneCode) ? $user : null;
        }
        return null;
    }

    protected function findByCredentials(array $credentials): ?User
    {
        return $this->withEmail(Arr::get($credentials, 'email'), Arr::get($credentials, 'password'))
            ?: $this->withUsername(Arr::get($credentials, 'username'), Arr::get($credentials, 'password'))
                ?: $this->withPhone(Arr::get($credentials, 'phone'), Arr::get($credentials, 'password'))
                    ?: $this->withPhoneCode(Arr::get($credentials, 'phone'), Arr::get($credentials, 'phoneCode'));
    }
}
