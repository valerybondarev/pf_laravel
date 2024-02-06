<?php

namespace App\Domain\Mailing\Services;


use App\Base\Services\BaseService;
use App\Domain\Mailing\Entities\Mailing;
use App\Base\Interfaces\ManageServiceInterface;
use App\Domain\Mailing\Entities\MailingButton;
use App\Domain\Mailing\Enums\MailingWorkingEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * This is the service class for table "mailings".
 * Class App\Domain\Mailing\Services\MailingService
 *
 * @package  App\Domain\Mailing\Services
 */
class MailingService extends BaseService implements ManageServiceInterface
{
    public function create(array $data): Mailing
    {
        $model = new Mailing();
        $model->working = MailingWorkingEnum::NOT_WORKING;
        return $this->update($model, $data);
    }

    public function update(Mailing|Model $model, array $data): Mailing
    {
        $model->title = Arr::get($data, 'title', $model->title);
        $model->text = Arr::get($data, 'text', $model->text);
        $model->working = Arr::get($data, 'working', $model->working);
        $model->status = Arr::get($data, 'status', $model->status);
        $model->send_at = Arr::get($data, 'sendAt', $model->send_at);

        if ($model->saveOrFail()) {
            $model->clientLists()->sync(Arr::get($data, 'clientLists', []));
            $model->buttons()->delete();
            foreach (Arr::get($data, 'buttons', []) as $button) {
                $button['json'] = json_encode($button['json'], 256);
                $mailingButton = new MailingButton($button);
                $mailingButton->mailing_id = $model->id;
                if (!$mailingButton->save()) {
                    dd($mailingButton->attributesToArray());
                }
            }
        }

        return $model;
    }

    public function destroy(Mailing|Model $model): bool
    {
        return $model->forceFill(['status' => 'deleted'])->save();
    }
}
