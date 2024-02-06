<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'statuses';

    protected $fillable = ['name', 'active'];

    public $timestamps = false;

    public static function getDefaultStatus() {
        $status = Status::where('active', 1)->first();
        return $status->id;
    }
}
