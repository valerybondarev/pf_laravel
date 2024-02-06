<?php


namespace App\Base\DataProviders;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Itstructure\GridView\Helpers\SortHelper;
use Str;

class EloquentDataProvider extends \Itstructure\GridView\DataProviders\EloquentDataProvider
{
    public function selectionConditions(Request $request, bool $strictFilters = false): void
    {
        if ($request->get('sort')) {
            $this->query->orderBy(SortHelper::getSortColumn($request), SortHelper::getDirection($request));
        }

        foreach (array_filter($request->get('filters', [])) as $column => $value) {
            if (is_null($value)) {
                continue;
            }

            Str::contains($column, '.')
                ? $this->whereNested($column, $value, $strictFilters)
                : $this->where($column, $value, $strictFilters);
        }
    }

    private function where($column, $value, bool $strict = false)
    {
        if ($strict || is_numeric($value)) {
            $this->query->where($column, '=', $value);
        } else {
            $this->query->where($column, 'like', '%' . $value . '%');
        }
    }

    private function whereNested($column, $value, bool $strict = false)
    {
        $nestedColumn = last(explode('.', $column));
        $relation = str_replace('.' . $nestedColumn, null, $column);

        $this->query->whereHas(
            $relation,
            fn(Builder $query) => $strict
                ? $query->where($nestedColumn, $value)
                : $query->where($nestedColumn, 'like', '%' . $value . '%')
        );
    }
}
