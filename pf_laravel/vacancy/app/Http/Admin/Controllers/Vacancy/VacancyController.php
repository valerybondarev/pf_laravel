<?php

namespace App\Http\Admin\Controllers\Vacancy;

use App\Domain\Catalog\Repositories\CategoryRepository;
use App\Http\Admin\Controllers\ResourceController;
use App\Domain\Vacancy\Entities\Vacancy;
use App\Domain\Vacancy\Repositories\VacancyRepository;
use App\Domain\Vacancy\Services\VacancyService;
use Illuminate\Http\Request;

/**
 * This is the controller class for table "vacancies".
 * Class App\Http\Admin\Controllers\Vacancy
 *
 * @package  App\Http\Admin\Controllers\Vacancy\VacancyController
 */
class VacancyController extends ResourceController
{
    public function __construct(
        VacancyRepository          $repository,
        VacancyService             $service,
        private CategoryRepository $categoryRepository,
    )
    {
        parent::__construct($repository, $service);
    }

    protected function rules($model = null): array
    {
        return [
            'categoryId' => 'required|numeric|exists:categories,id',
            'text'       => 'required|string',
        ];
    }

    protected function resourceClass(): string
    {
        return Vacancy::class;
    }

    protected function viewParameters(): array
    {
        return [
            'categories' => $this->categoryRepository->allActive()->keyBy('id')->map->title,
        ];
    }
}
