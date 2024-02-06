<?php

namespace App\Http\Admin\Controllers\Catalog;

use App\Domain\Catalog\Entities\Category;
use App\Domain\Catalog\Repositories\CategoryRepository;
use App\Domain\Catalog\Services\CategoryService;
use App\Http\Admin\Controllers\ResourceController;


class CategoryController extends ResourceController
{
    protected bool $supportsShowMethod = false;

    public function __construct(CategoryRepository $repository, CategoryService $service)
    {
        parent::__construct($repository, $service);
    }

    protected function rules($model = null): array
    {
        return [
            'title'      => 'required|string|max:255',
            'status'     => 'required|string|max:255',
            'image'      => 'nullable|string',
            'alias'      => 'nullable|string',
            'keyWords'   => 'nullable|array',
            'keyWords.*' => 'required|string',
        ];
    }

    protected function resourceClass(): string
    {
        return Category::class;
    }
}
