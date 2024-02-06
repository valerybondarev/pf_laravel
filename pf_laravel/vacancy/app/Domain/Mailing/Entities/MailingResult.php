<?php

namespace App\Domain\Mailing\Entities;


use App\Base\Models\BaseModel;
use Eloquent;
use App\Domain\Client\Entities\Client;
use Carbon\Carbon;

/**
 * This is the model class for table "mailing_results".
 * Class MailingResult
 *
 * @package  App\Domain\Mailing\Entities\MailingResult
 * @mixin  Eloquent
 * @property  int     $id
 * @property  int     $mailing_id
 * @property  int     $client_id
 * @property  int     $success
 * @property  string  $error
 * @property  Carbon  $clicked_at
 * @property  Carbon  $created_at
 * @property  Carbon  $updated_at
 *
 * @property  Client  $client
 * @property  Mailing $mailing
 *
 */
class MailingResult extends BaseModel
{
    protected $table = 'mailing_results';
    
    protected $dates = ['clicked_at'];

    protected $fillable = ['mailing_id', 'client_id', 'success', 'error', 'clicked_at', 'created_at'];

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function mailing()
    {
        return $this->hasOne(Mailing::class, 'id', 'mailing_id');
    }
}
