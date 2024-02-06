<?php

namespace App\Domain\Catalog\Entities;


use App\Base\Models\BaseModel;
use Eloquent;
use Illuminate\Support\Collection;
use App\Domain\Catalog\Entities\Category;

/**
 * This is the model class for table "category_key_words".
 * Class CategoryKeyWord
 *
 * @package  App\Domain\Catalog\Entities\CategoryKeyWord
 * @mixin  Eloquent
 * @property  int      $id
 * @property  int      $category_id
 * @property  string   $title
 *
 * @property  Category $category
 *
 */
class CategoryKeyWord extends BaseModel
{
    protected $table = 'category_key_words';

    public $timestamps = false;

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
