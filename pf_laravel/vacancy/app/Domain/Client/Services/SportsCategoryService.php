<?php

namespace App\Domain\Client\Services;


use App\Base\Services\BaseService;
use App\Domain\Client\Entities\SportsCategory;
use App\Base\Interfaces\ManageServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * This is the service class for table "sports_categories".
 * Class App\Domain\Client\Services\SportsCategoryService
 *
 * @package  App\Domain\Client\Services
 * @method SportsCategory|null findActive(array $params = [])
 */
class SportsCategoryService extends BaseService implements ManageServiceInterface
{
    public function create(array $data): SportsCategory
    {
        $model = new SportsCategory();
        return $this->update($model, $data);
    }

    public function update(SportsCategory|Model $model, array $data): SportsCategory
    {
        $model->title = Arr::get($data, 'title', $model->title);
        $model->status = Arr::get($data, 'status', $model->status);

        $model->saveOrFail();

        if (isset($data['clientsList'])) {
            $model->clients()->detach($model->clients);
            $model->clients()->attach($data['clientsList']);
        }
        return $model;
    }

    public function destroy(SportsCategory|Model $model): bool
    {
        return $model->forceFill(['status' => 'deleted'])->save();
    }
}
