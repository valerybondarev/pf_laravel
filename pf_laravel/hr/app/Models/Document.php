<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'documents';

    protected $fillable = ['name', 'user_id', 'url', 'crated_at', 'updated_at'];

    public $timestamps = true;

}
