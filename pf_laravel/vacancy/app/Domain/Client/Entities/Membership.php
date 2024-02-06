<?php

namespace App\Domain\Client\Entities;


use App\Base\Models\BaseModel;
use Eloquent;
use App\Domain\TourClub\Entities\TourClub;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * This is the model class for table "memberships".
 * Class Membership
 *
 * @package  App\Domain\Client\Entities\Membership
 * @mixin  Eloquent
 * @property  int      $id
 * @property  int      $tour_clubs
 * @property  int      $client_id
 * @property  Carbon   $extended_to
 * @property  Carbon   $created_at
 * @property  Carbon   $updated_at
 *
 * @property  Client   $client
 * @property  TourClub $tourClub
 *
 */
class Membership extends BaseModel
{
    protected $table = 'memberships';
    protected $fillable = ['status', 'extended_to'];
    protected $dates = ['extended_to'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function tourClub(): BelongsTo
    {
        return $this->belongsTo(TourClub::class);
    }
}
