<?php

namespace App\Domain\Authentication\Interfaces;

use App\Domain\User\Entities\User;
use Illuminate\Contracts\Auth\Authenticatable;

interface AuthenticationInterface
{
    public function login(array $credentials): mixed;

    public function logout(User $user): bool;

    public function check(): bool;

    public function user(): Authenticatable|User|null;
}
