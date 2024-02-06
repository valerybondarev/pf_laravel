<?php

namespace App\Domain\Client\Services;


use App\Base\Services\BaseService;
use App\Domain\Client\Entities\ClientList;
use App\Base\Interfaces\ManageServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * This is the service class for table "client_lists".
 * Class App\Domain\Client\Services\ClientListService
 *
 * @package  App\Domain\Client\Services
 */
class ClientListService extends BaseService implements ManageServiceInterface
{
    public function create(array $data): ClientList
    {
        $model = new ClientList();
        return $this->update($model, $data);
    }

    public function update(ClientList|Model $model, array $data): ClientList
    {
        $model->title = Arr::get($data, 'title', $model->title);
        $model->status = Arr::get($data, 'status', $model->status);
        $model->tour_club_id = Arr::get($data, 'tourClubId', $model->tour_club_id);

        if ($model->saveOrFail()) {
            $model->clients()->sync(Arr::get($data, 'clients', []));
        }

        return $model;
    }

    public function destroy(ClientList|Model $model): bool
    {
        return $model->forceFill(['status' => 'deleted'])->save();
    }
}
