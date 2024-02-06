<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Levels extends Model
{
    protected $table = 'levels';
    protected $fillable = ['name'];
    public $timestamps = false;
}
