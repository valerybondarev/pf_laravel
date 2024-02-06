<?php

namespace App\Domain\Client\Services;


use App\Base\Services\BaseService;
use App\Domain\Client\Entities\Membership;
use App\Base\Interfaces\ManageServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * This is the service class for table "memberships".
 * Class App\Domain\Client\Services\MembershipService
 *
 * @package  App\Domain\Client\Services
 * @method Membership|null findActive(array $params = [])
 */
class MembershipService extends BaseService implements ManageServiceInterface
{
    public function create(array $data): Membership
    {
        $model = new Membership();
        return $this->update($model, $data);
    }

    public function update(Membership|Model $model, array $data): Membership
    {
        $model->client_id = Arr::get($data, 'clientId', $model->client_id);
        $model->status = Arr::get($data, 'status', $model->status);
        $model->extended_to = Arr::get($data, 'extendedTo', $model->extended_to);

        $model->saveOrFail();

        return $model;
    }

    public function destroy(Membership|Model $model): bool
    {
        return $model->forceFill(['status' => 'deleted'])->save();
    }
}
