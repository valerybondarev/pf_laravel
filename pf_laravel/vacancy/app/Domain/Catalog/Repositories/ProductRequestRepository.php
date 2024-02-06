<?php

namespace App\Domain\Catalog\Repositories;

use App\Base\Repositories\BaseEloquentRepository;
use App\Domain\Catalog\Entities\ProductRequest;

/**
 * Class ProductRequestRepository
 *
 * @package App\Domain\News\Repositories
 *
 * @method ProductRequest|null findActive(array $params = [])
 * @method ProductRequest|null oneActive(int $id)
 */
class ProductRequestRepository extends BaseEloquentRepository
{
    protected function modelClass(): string
    {
        return ProductRequest::class;
    }
}
