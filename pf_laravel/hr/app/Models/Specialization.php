<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    protected $table = 'specializations';
    protected $fillable = ['name'];
    public $timestamps = false;
}
