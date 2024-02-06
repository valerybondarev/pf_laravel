<?php

namespace App\Domain\Catalog\Entities;



use App\Base\Models\BaseModel;
use App\Domain\Application\File\Entities\Image;
use App\Domain\Catalog\Enums\BrandStatus;
use Eloquent;
use Carbon\Carbon;

/**
* This is the model class for table "admin_brand".
* Class AdminBrand
* @package  App\Domain\Catalog\Entities\
* @mixin  Eloquent
* @property  int         $id
* @property  string      $title
* @property  string      $status
* @property  string|null $image_id
* @property  Carbon|null $created_at
* @property  Carbon|null $updated_at
*
*
*/

class AdminBrand extends BaseModel
{
    protected $table = 'admin_brand';


    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function getStatus()
    {
        return BrandStatus::label($this->status);
    }

    public function getCreatedAt()
    {
        return $this->created_at ? date('Y-m-d', strtotime($this->created_at)) : null;
    }
}
