<?php

namespace App\Console\Commands;

use App\Domain\User\Repositories\UserRepository;
use Illuminate\Console\Command;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

class UserAssignRoleCommand extends Command
{
    protected $signature = 'user:assignRole {userID} {role}';

    public function __construct(
        private UserRepository $userRepository
    )
    {
        parent::__construct();
    }

    public function handle(): int
    {
        if ($user = $this->userRepository->one($userID = $this->argument('userID'))) {
            $role = $this->argument('role');
            try {
                $user->assignRole($role);

                $this->info('Role assigned successfully');
            } catch (RoleDoesNotExist) {
                $this->error("Unknown role: $role");
            }
        } else {
            $this->error("Unknown userID: $userID");
        }

        return 0;
    }
}
