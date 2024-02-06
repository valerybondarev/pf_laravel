<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LanguageLevel extends Model
{
    protected $table = 'language_levels';
    protected $fillable = ['name'];
    public $timestamps = false;
}
