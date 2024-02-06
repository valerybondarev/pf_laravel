<?php

namespace App\Domain\User\Repositories;

use App\Base\Interfaces\DataProviderInterface;
use App\Base\Repositories\BaseEloquentRepository;
use App\Base\Traits\DataProviderTrait;
use App\Domain\User\Entities\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * @method User|null findActive(array $parameters = [])
 */
class UserRepository extends BaseEloquentRepository implements DataProviderInterface
{
    use DataProviderTrait;

    protected function modelClass(): string
    {
        return User::class;
    }

    protected function applyId(User|Builder $query, $id)
    {
        $query->where('id', $id);
    }

    protected function applyEmail(User|Builder $query, $email)
    {
        $query->where('email', $email);
    }

    protected function applyPhone(User|Builder $query, $phone)
    {
        $query->where('phone', $phone);
    }

    protected function applyUsername(User|Builder $query, $username)
    {
        $query->where('username', $username);
    }

    protected function applySearch(User|Builder $query, $search)
    {
        foreach (Str::of($search)->explode(' ') as $searchPart) {
            $query->where(function (Builder $query) use ($searchPart) {
                $query
                    ->where('last_name', 'like', "%$searchPart%")
                    ->orWhere('first_name', 'like', "%$searchPart%")
                    ->orWhere('middle_name', 'like', "%$searchPart%");
            });
        }
    }
}
