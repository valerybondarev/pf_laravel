<?php

namespace App\Domain\Client\Services;


use App\Base\Services\BaseService;
use App\Domain\Client\Entities\Source;
use App\Base\Interfaces\ManageServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * This is the service class for table "sources".
 * Class App\Domain\Client\Services\SourceService
 *
 * @package  App\Domain\Client\Services
 */
class SourceService extends BaseService implements ManageServiceInterface
{
    public function create(array $data): Source
    {
        $model = new Source();
        return $this->update($model, $data);
    }

    public function update(Source|Model $model, array $data): Source
    {
        $model->tour_club_id = Arr::get($data, 'tourClubId', $model->tour_club_id);
        $model->title = Arr::get($data, 'title', $model->title);
        $model->description = Arr::get($data, 'description', $model->description);
        $model->status = Arr::get($data, 'status', $model->status);

        $model->saveOrFail();

        if (isset($data['clientsList'])) {
            $model->clients()->detach($model->clients);
            $model->clients()->attach($data['clientsList']);
        }
        return $model;
    }

    public function destroy(Source|Model $model): bool
    {
        return $model->forceFill(['status' => 'deleted'])->save();
    }
}
