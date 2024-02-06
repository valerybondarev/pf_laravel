<?php

namespace App\Domain\Vacancy\Services;

use App\Base\Services\BaseService;
use App\Domain\Vacancy\Entities\Vacancy;
use App\Base\Interfaces\ManageServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * This is the service class for table "vacancies".
 * Class App\Domain\Vacancy\Services\VacancyService
 *
 * @package  App\Domain\Vacancy\Services
 */
class VacancyService extends BaseService implements ManageServiceInterface
{
    public function create(array $data): Vacancy
    {
        $model = new Vacancy();
        return $this->update($model, $data);
    }

    public function update(Vacancy|Model $model, array $data): Vacancy
    {
        $model->category_id = Arr::get($data, 'categoryId', $model->category_id);
        $model->text = Arr::get($data, 'text', $model->text);

        $model->saveOrFail();

        return $model;
    }

    public function destroy(Vacancy|Model $model): bool
    {
        return $model->forceFill(['status' => 'deleted'])->save();
    }
}
