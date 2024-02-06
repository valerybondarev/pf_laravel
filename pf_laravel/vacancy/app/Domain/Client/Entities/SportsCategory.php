<?php

namespace App\Domain\Client\Entities;


use App\Base\Models\BaseModel;
use Eloquent;
use Illuminate\Support\Collection;
use App\Domain\Client\Entities\Client;
use Carbon\Carbon;

/**
 * This is the model class for table "sports_categories".
 * Class SportsCategory
 *
 * @package  App\Domain\Client\Entities\SportsCategory
 * @mixin  Eloquent
 * @property  int       $id
 * @property  string    $title
 * @property  string    $status
 * @property  Carbon    $created_at
 * @property  Carbon    $updated_at
 *
 *
 * @property  Client[]|Collection $clients
 */
class SportsCategory extends BaseModel
{
    protected $table = 'sports_categories';
    

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'clients');
    }
}
