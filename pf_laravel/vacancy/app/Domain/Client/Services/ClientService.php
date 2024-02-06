<?php

namespace App\Domain\Client\Services;


use App\Base\Services\BaseService;
use App\Domain\Client\Entities\Client;
use App\Base\Interfaces\ManageServiceInterface;
use App\Domain\Client\Enums\ClientRoleEnum;
use App\Domain\Client\Enums\ClientStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * This is the service class for table "clients".
 * Class App\Domain\Client\Services\ClientService
 *
 * @package  App\Domain\Client\Services
 * @method Client|null findActive(array $params = [])
 */
class ClientService extends BaseService implements ManageServiceInterface
{
    public function create(array $data): Client
    {
        $model = new Client();
        $model->status = ClientStatusEnum::ACTIVE;
        $model->role = ClientRoleEnum::USER;
        $model->start = 0;
        $model->mailing_news = 0;
        $model->mailing_events = 0;
        $model->status_learn = 0;
        return $this->update($model, $data);
    }

    public function update(Client|Model $model, array $data): Client
    {
        $model->tour_club_id = Arr::get($data, 'tourClubId', $model->tour_club_id);
        $model->sports_category_id = Arr::get($data, 'sportsCategoryId', $model->sports_category_id);
        $model->command = Arr::get($data, 'command', $model->command);
        $model->telegram_id = Arr::get($data, 'telegramId', $model->telegram_id);
        $model->username = Arr::get($data, 'username', $model->username);
        $model->first_name = Arr::get($data, 'firstName', $model->first_name);
        $model->last_name = Arr::get($data, 'lastName', $model->last_name);
        $model->middle_name = Arr::get($data, 'middleName', $model->middle_name);
        $model->phone = Arr::get($data, 'phone', $model->phone);
        $model->year_in_tk = Arr::get($data, 'yearInTk', $model->year_in_tk);
        $model->status_learn = Arr::get($data, 'statusLearn', $model->status_learn);
        $model->year_in_university = Arr::get($data, 'yearInUniversity', $model->year_in_university);
        $model->department = Arr::get($data, 'department', $model->department);
        $model->vk_link = Arr::get($data, 'vkLink', $model->vk_link);
        if ($model->department) {
            $model->department = mb_strtoupper($model->department);
        }
        $model->group = Arr::get($data, 'group', $model->group);
        if ($model->group) {
            $model->group = mb_strtoupper($model->group);
        }
        $model->live_in_dorm = Arr::get($data, 'liveInDorm', $model->live_in_dorm);
        $model->work_organization = Arr::get($data, 'workOrganization', $model->work_organization);
        $model->camping_experience = Arr::get($data, 'campingExperience', $model->camping_experience);
        $model->status = Arr::get($data, 'status', $model->status);
        $model->role = Arr::get($data, 'role', $model->role);
        $model->registered_at = Arr::get($data, 'registeredAt', $model->registered_at);
        $model->points = Arr::get($data, 'points', $model->points);
        $model->mailing_news = Arr::get($data, 'mailingNews', $model->mailing_news);
        $model->mailing_events = Arr::get($data, 'mailingEvents', $model->mailing_events);
        $model->start = Arr::get($data, 'start', $model->start);
        $model->born_at = Arr::get($data, 'bornAt', $model->born_at);

        $model->saveOrFail();

        if (isset($data['clientMessagesList'])) {
            $model->clientMessages()->detach($model->clientMessages);
            $model->clientMessages()->attach($data['clientMessagesList']);
        }
        return $model;
    }

    public function destroy(Client|Model $model): bool
    {
        return $model->forceFill(['status' => 'deleted'])->save();
    }
}
