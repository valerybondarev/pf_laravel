<?php

namespace App\Domain\Mailing\Repositories;

use App\Base\Repositories\BaseEloquentRepository;
use App\Domain\Mailing\Entities\MailingButton;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Base\Interfaces\DataProviderInterface;

use App\Base\Traits\DataProviderTrait;
/**
* This is the repository class for table "mailing_buttons".
* Class MailingButtonRepository
*
* @package  App\Domain\Mailing\Repositories
 * @method MailingButton []|Collection search(array $parameters = [], int $limit = null)
 * @method MailingButton []|Collection searchActive(array $parameters = [], int $limit = null)
 * @method MailingButton|null one(string|int|null $id, bool $active = false)
 * @method MailingButton|null oneActive(array $params = [])
 * @method MailingButton|null find(array $params = [])
 * @method MailingButton|null findActive(array $params = [])
*/
class MailingButtonRepository extends BaseEloquentRepository implements DataProviderInterface
{
    use DataProviderTrait;

    protected function modelClass(): string
    {
        return MailingButton::class;
    }

    protected function applyParameters(Builder $query, array $parameters = []): Builder
    {
        return parent::applyParameters($query, $parameters)
            ->when(Arr::get($parameters, 'paramName'), fn(Builder $query, $value) => $query->where('paramName', $value))
            ;
    }
}
