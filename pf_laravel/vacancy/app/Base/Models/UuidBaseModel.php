<?php

namespace App\Base\Models;

use Illuminate\Support\Str;

abstract class UuidBaseModel extends BaseModel
{
    protected $keyType      = 'string';
    public    $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($model) => $model->setAttribute($model->getKeyName(), Str::uuid()));
    }
}
