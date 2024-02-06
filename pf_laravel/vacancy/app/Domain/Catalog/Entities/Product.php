<?php

namespace App\Domain\Catalog\Entities;


use App\Base\Models\BaseModel;
use App\Domain\Application\File\Entities\File;
use App\Domain\Application\File\Entities\Image;
use App\Domain\Catalog\Enums\ProductStatus;
use Carbon\Carbon;
use Eloquent;

/**
 * Class Product
 *
 * @package App\Domain\Catalog\Entities
 * @mixin Eloquent
 *
 * @property int         $id
 * @property integer     $subcategory_id
 * @property string      $brand_id
 * @property string      $image_id
 * @property string      $alias
 * @property string      $id_in_catalog
 * @property string      $title
 * @property Carbon      $imported_at
 * @property string      $price
 * @property string      $vendor_code
 * @property string      $stock
 *
 *
 * @property string      $status
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 *
 * @property Image       $image
 * @property Subcategory $subcategory
 *
 */
class Product extends BaseModel
{

    protected $table = 'product';

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getStatus()
    {
        return ProductStatus::label($this->status);
    }

    public function image()
    {
        return $this->hasOne(Image::class, 'id', 'image_id');
    }

    public function subcategory()
    {
        return $this->hasOne(Subcategory::class, 'id', 'subcategory_id');
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    public function getCreatedAt()
    {
        return $this->created_at ? date('Y-m-d', strtotime($this->created_at)) : null;
    }
}
