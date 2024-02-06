<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'user_roles';

    protected $fillable = ['role_id', 'user_id'];

    public $timestamps = false;
}
