<?php

namespace App\Domain\Catalog\Entities;



use App\Base\Models\BaseModel;
use App\Domain\Application\File\Entities\File;
use App\Domain\Application\File\Entities\Image;
use App\Domain\Catalog\Enums\SubcategoryStatus;
use App\Domain\News\Enums\NewsStatus;
use Carbon\Carbon;
use Eloquent;

/**
 * Class Category
 * @package App\Domain\News\Entities
 * @mixin Eloquent
 *
 * @property int $id
 * @property integer $category_id
 * @property string $title
 * @property string $alias
 *
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Product [] $products
 *
 */
class Subcategory extends BaseModel
{
    protected $table = 'subcategory';

    public function getTitle()
    {
        return $this->title;
    }

    public function getStatus()
    {
        return SubcategoryStatus::label($this->status);
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'subcategory_id', 'id');
    }

    public function getCreatedAt()
    {
        return $this->created_at ? date('Y-m-d', strtotime($this->created_at)) : null;
    }
}
