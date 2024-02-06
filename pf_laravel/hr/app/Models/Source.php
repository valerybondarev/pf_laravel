<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;

    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'sources';

    protected $fillable = ['name'];

    public $timestamps = false;
}
