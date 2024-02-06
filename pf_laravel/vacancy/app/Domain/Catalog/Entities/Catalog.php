<?php

namespace App\Domain\Catalog\Entities;


use App\Base\Models\BaseModel;
use App\Domain\Application\File\Entities\File;
use App\Domain\Application\File\Entities\Image;
use App\Domain\News\Enums\NewsStatus;
use Eloquent;

/**
 * Class Product
 * @package App\Domain\Catalog\Entities
 *
 * @property string $document
 *
 *
 */
class Catalog
{
    public $document;

    public $categories;

    public $countImportProducts;
}
