<?php

namespace App\Domain\Authentication\Services;


use App\Domain\User\Entities\User;
use Arr;
use Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;

class WebAuthenticationService extends AuthenticationService
{
    private function guard(): Guard|StatefulGuard
    {
        return Auth::guard('web');
    }

    public function login(array $credentials = []): bool
    {
        if ($user = $this->findByCredentials($credentials)) {
            $this->guard()->login($user, Arr::get($credentials, 'remember'));
            return true;
        }
        return false;
    }

    public function logout(User $user): bool
    {
        $this->guard()->logout();
        return true;
    }

    public function check(): bool
    {
        return $this->guard()->check();
    }

    public function user(): Authenticatable|User|null
    {
        return $this->guard()->user();
    }
}
