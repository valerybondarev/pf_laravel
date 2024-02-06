<?php

namespace App\Domain\Application\Language\Repositories;

use App\Base\Repositories\BaseEloquentRepository;
use App\Domain\Application\Language\Entities\Language;
use Illuminate\Database\Eloquent\Builder;

class LanguageRepository extends BaseEloquentRepository
{
    protected function modelClass(): string
    {
        return Language::class;
    }

    protected function active(): Builder
    {
        return parent::active()->orderByDesc('is_default');
    }

    public function applyCode(Builder $query, $code)
    {
        $query->where('code', $code);
    }

    public function applyTitle(Builder $query, $title)
    {
        $query->where('title', $title);
    }

    public function applyIsDefault(Builder $query, $isDefault)
    {
        $query->where('is_default', $isDefault);
    }
}
