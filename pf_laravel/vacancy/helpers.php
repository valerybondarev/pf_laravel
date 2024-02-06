<?php

use Illuminate\Database\Eloquent\Builder;

if (!function_exists('find_file')) {
    function find_file($id): \App\Domain\Application\File\Entities\File|Builder|null
    {
        return app(\App\Domain\Application\File\Repositories\FileRepository::class)->one($id);
    }
}
if (!function_exists('find_image')) {
    function find_image($id): \App\Domain\Application\File\Entities\Image|Builder|null
    {
        return app(\App\Domain\Application\File\Repositories\ImageRepository::class)->one($id);
    }
}