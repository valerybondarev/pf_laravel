<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Field;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'first_name',
        'middle_name',
        'last_name',
        'name_en',
        'email',
        'password',
        'role_id',
        'slug'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $connection = 'mysql';

    public function worker()
    {
        return $this->hasOne('App\Models\Worker', 'user_id');
    }

    public function role()
    {
        return $this->hasOne('App\Models\Role', 'id', 'role_id');
    }

    public function documents()
    {
        return $this->hasMany('App\Models\Document', 'user_id');
    }

    public function experiences()
    {
        return $this->hasMany('App\Models\Experience', 'user_id');
    }

    public function delete() {
        $this->worker()->delete();
        $this->documents()->delete();
        $this->experiences()->delete();
        return parent::delete();
    }

    public static function generateUniqueCode()
    {
        do {
            $referal_code = strtolower(Str::random(20));
        } while (self::where("slug", "=", $referal_code)->first());

        return $referal_code;
    }
}
