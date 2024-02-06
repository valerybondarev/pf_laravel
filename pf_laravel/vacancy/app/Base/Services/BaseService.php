<?php


namespace App\Base\Services;


use App\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class BaseService
{
    private function isSlugExists(BaseModel $model, $slug, $slugAttribute, $ignoreId = null): bool
    {
        return $model->newModelQuery()
            ->where($slugAttribute, $slug)
            ->when($ignoreId, fn(Builder $query, $ignoreId) => $query->where($model->getKeyName(), '!=', $ignoreId))
            ->exists();
    }

    protected function slug(BaseModel $model, $string, $ignoreId = null, $slugAttribute = 'slug'): string
    {
        $slug = Str::slug($string);
        $count = 1;

        while ($this->isSlugExists($model, $slug, $slugAttribute, $ignoreId)) {
            $slug = Str::slug("$string-$count");
            $count++;
        }

        return $slug;
    }

}
