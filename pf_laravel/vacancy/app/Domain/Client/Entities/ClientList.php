<?php

namespace App\Domain\Client\Entities;


use App\Base\Models\BaseModel;
use App\Domain\Mailing\Entities\Mailing;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Carbon\Carbon;

/**
 * This is the model class for table "client_lists".
 * Class ClientList
 *
 * @package  App\Domain\Client\Entities\ClientList
 * @mixin  Eloquent
 * @property  int                 $id
 * @property  string              $title
 * @property  string              $tour_club_id
 * @property  string              $status
 * @property  Carbon              $created_at
 * @property  Carbon              $updated_at
 *
 *
 * @property  Client[]|Collection $clients
 * @property  Mailing[]|Collection $mailings
 */
class ClientList extends BaseModel
{
    protected $table = 'client_lists';
    

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'client_list_client')->orderBy('first_name');
    }

    public function mailings()
    {
        return $this->belongsToMany(Mailing::class, 'mailing_client_list');
    }
}
