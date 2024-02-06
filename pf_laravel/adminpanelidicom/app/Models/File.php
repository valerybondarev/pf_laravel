<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $disk
 * @property string $path
 * @property string|null $original
 * @property string|null $ext
 * @property string|null $mime
 */
class File extends Model
{
    protected $fillable = [
        'disk',
        'path',
        'original',
        'ext',
        'mime',
    ];
}
