<?php

namespace App\Domain\Mailing\Entities;


use App\Base\Models\BaseModel;
use App\Domain\Client\Entities\ClientList;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Carbon\Carbon;

/**
 * This is the model class for table "mailings".
 * Class Mailing
 *
 * @package  App\Domain\Mailing\Entities\Mailing
 * @mixin  Eloquent
 * @property  int                        $id
 * @property  int                        $client_list_id
 * @property  string                     $title
 * @property  string                     $text
 * @property  int                        $working
 * @property  string                     $status
 * @property  Carbon                     $send_at
 * @property  Carbon                     $created_at
 * @property  Carbon                     $updated_at
 *
 *
 * @property  MailingResult[]|Collection $mailingResults
 * @property  ClientList[]|Collection    $clientLists
 * @property  MailingButton[]|Collection $buttons
 */
class Mailing extends BaseModel
{
    protected $table = 'mailings';

    protected $dates = ['send_at'];

    public function mailingResults(): HasMany
    {
        return $this->hasMany(MailingResult::class);
    }

    public function clientLists()
    {
        return $this->belongsToMany(ClientList::class, 'mailing_client_list');
    }

    public function buttons()
    {
        return $this->hasMany(MailingButton::class);
    }
}
