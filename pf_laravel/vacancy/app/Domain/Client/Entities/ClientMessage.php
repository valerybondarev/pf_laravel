<?php

namespace App\Domain\Client\Entities;


use App\Base\Models\BaseModel;
use Eloquent;
use Illuminate\Support\Collection;
use App\Domain\Client\Entities\Client;
use Carbon\Carbon;

/**
 * This is the model class for table "client_messages".
 * Class ClientMessage
 *
 * @package  App\Domain\Client\Entities\ClientMessage
 * @mixin  Eloquent
 * @property  int    $id
 * @property  int    $client_id
 * @property  string $text
 * @property  int    $is_admin
 * @property  string $status
 * @property  Carbon $created_at
 * @property  Carbon $updated_at
 *
 * @property  Client $client
 *
 */
class ClientMessage extends BaseModel
{
    protected $table = 'client_messages';
    

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }
}
