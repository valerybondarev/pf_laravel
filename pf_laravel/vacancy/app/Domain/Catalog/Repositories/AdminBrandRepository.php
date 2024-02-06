<?php

namespace App\Domain\Catalog\Repositories;

use App\Base\Repositories\BaseEloquentRepository;
use App\Domain\Catalog\Entities\AdminBrand;
use App\Domain\Catalog\Enums\AdminBrandStatus;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ProductRepository
 *
 * @package App\Domain\News\Repositories
 *
 * @method AdminBrand|null findActive(array $params = [])
 */
class AdminBrandRepository extends BaseEloquentRepository
{
    protected function modelClass(): string
    {
        return AdminBrand::class;
    }

    protected function query(): Builder
    {
        return parent::query()->where('status', '!=', AdminBrandStatus::DELETED);
    }

    protected function active(): Builder
    {
        return $this->query()->where('status', AdminBrandStatus::ACTIVE);
    }

    public function getLast($limit = 4)
    {
        return $this->active()->latest('published_at')->orderBy('published_at', 'ASC')->orderBy('id', 'DESC')->limit($limit)->get();
    }

    public function getByTitle($title)
    {
        return parent::query()->where('title', '=', $title)->first();
    }
}
