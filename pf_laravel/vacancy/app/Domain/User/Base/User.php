<?php

namespace App\Domain\User\Base;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

abstract class User extends Authenticatable
{
    use SoftDeletes;
}