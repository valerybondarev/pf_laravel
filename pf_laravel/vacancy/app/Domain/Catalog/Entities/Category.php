<?php

namespace App\Domain\Catalog\Entities;


use App\Base\Models\BaseModel;
use App\Domain\Application\File\Entities\File;
use App\Domain\Application\File\Entities\Image;
use App\Domain\Catalog\Enums\CategoryStatus;
use App\Domain\Catalog\Enums\SubcategoryStatus;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Category
 *
 * @package App\Domain\News\Entities
 * @mixin Eloquent
 *
 * @property int               $id
 * @property string            $title
 * @property string            $alias
 * @property string            $image
 *
 * @property string            $status
 * @property Carbon            $created_at
 * @property Carbon            $updated_at
 *
 * @property File              $file
 * @property Subcategory[]     $subcategories
 * @property CategoryKeyWord[] $keyWords
 *
 */
class Category extends BaseModel
{
    use SoftDeletes;
    protected $table = 'categories';

    public function getTitle()
    {
        return $this->title;
    }

    public function getStatus()
    {
        return CategoryStatus::label($this->status);
    }

    public function file()
    {
        return $this->hasOne(Image::class, 'id', 'image');
    }

    public function getCreatedAt()
    {
        return $this->created_at ? date('Y-m-d', strtotime($this->created_at)) : null;
    }

    public function keyWords()
    {
        return $this->hasMany(CategoryKeyWord::class, 'category_id', 'id');
    }
}
