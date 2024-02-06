<?php

namespace App\Domain\User\Repositories;

use App\Base\Repositories\BaseEloquentRepository;
use App\Domain\User\Entities\UserSocialProvider;
use Illuminate\Database\Eloquent\Builder;

class UserSocialProviderRepository extends BaseEloquentRepository
{
    protected function modelClass(): string
    {
        return UserSocialProvider::class;
    }

    private function applyUserId(UserSocialProvider|Builder $query, $userId)
    {
        $query->where('user_id', $userId);
    }

    private function applySource(UserSocialProvider|Builder $query, $source)
    {
        $query->where('source', $source);
    }

    private function applySourceId(UserSocialProvider|Builder $query, $sourceId)
    {
        $query->where('source_id', $sourceId);
    }
}
