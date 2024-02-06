<?php

namespace App\Domain\Mailing\Entities;


use App\Base\Models\BaseModel;
use Eloquent;

/**
 * This is the model class for table "mailing_buttons".
 * Class MailingButton
 *
 * @package  App\Domain\Mailing\Entities\MailingButton
 * @mixin  Eloquent
 * @property  int     $id
 * @property  int     $mailing_id
 * @property  string  $title
 * @property  string  $action
 * @property  string  $json
 * @property  int     $sort
 *
 * @property  Mailing $mailing
 *
 */
class MailingButton extends BaseModel
{
    protected $table      = 'mailing_buttons';
    public    $timestamps = false;
    protected $fillable   = ['title', 'action', 'json'];

    public function mailing()
    {
        return $this->hasOne(Mailing::class, 'id', 'mailing_id');
    }

    public function getJson()
    {
        return json_decode($this->json, true);
    }
}
