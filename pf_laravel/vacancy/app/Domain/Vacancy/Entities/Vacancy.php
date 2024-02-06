<?php

namespace App\Domain\Vacancy\Entities;


use App\Base\Models\BaseModel;
use App\Domain\Catalog\Entities\Category;
use Eloquent;
use Illuminate\Support\Collection;
use Carbon\Carbon;

/**
 * This is the model class for table "vacancies".
 * Class Vacancy
 *
 * @package  App\Domain\Vacancy\Entities\Vacancy
 * @mixin  Eloquent
 * @property  int    $id
 * @property  int    $category_id
 * @property  string $text
 * @property  Carbon $created_at
 * @property  Carbon $updated_at
 * @property  Carbon $deleted_at
 *
 *
 */
class Vacancy extends BaseModel
{
    protected $table = 'vacancies';

    protected $dates = ['deleted_at'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
