<?php
/**
 * @var BaseColumn[] $columnObjects
 * @var LengthAwarePaginator $paginator
 * @var bool $useFilters
 * @var string|null $rowsFormAction
 */

use Illuminate\Pagination\LengthAwarePaginator;
use Itstructure\GridView\Columns\BaseColumn;

?>
<div class="card shadow">
    <div class="card-header bg-transparent border-0">
        <div class="row align-items-center">
            <div class="col-8">
                @if($title)
                    <h3 class="mb-0">{!! $title !!}</h3>
                @endif
            </div>
            <div class="col-4 text-right">
                <a href="{{ URL::current() }}" class="btn btn-primary">{{ __('admin.actions.reset') }}</a>
                @if($rowsFormAction)
                    <a href="{{ $rowsFormAction }}" class="btn btn-primary">{{ __('admin.actions.create') }}</a>
                @endif
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-items-center table-flush">
            <thead class="thead-light">
            <tr>
                <th>#</th>
                @foreach($columnObjects as $column_obj)
                    <th {!! $column_obj->buildHtmlAttributes() !!}>

                        @if($column_obj->getSort() === false || $column_obj instanceof \Itstructure\GridView\Columns\ActionColumn)
                            {{ $column_obj->getLabel() }}

                        @elseif($column_obj instanceof \Itstructure\GridView\Columns\CheckboxColumn)
                            @if($useFilters)
                                {{ $column_obj->getLabel() }}
                            @else
                                <input type="checkbox" id="grid_view_checkbox_main" class="form-control form-control-sm"
                                       @if($paginator->count() == 0) disabled="disabled" @endif />
                            @endif

                        @else
                            <a href="{{ \Itstructure\GridView\Helpers\SortHelper::getSortableLink(request(), $column_obj) }}">{{ $column_obj->getLabel() }}</a>
                        @endif

                    </th>
                @endforeach
            </tr>
            @if ($useFilters)
                <tr>
                    <form action="" method="get" id="grid_view_filters_form">
                        <td></td>
                        @foreach($columnObjects as $column_obj)
                            <td>
                                {!! $column_obj->getFilter()->render() !!}
                            </td>
                        @endforeach
                        <input type="submit" class="d-none">
                    </form>
                </tr>
            @endif
            </thead>
            <tbody>
            @foreach($paginator->items() as $key => $row)
                <tr>
                    <td>{{ ($paginator->currentPage() - 1) * $paginator->perPage() + $key + 1 }}</td>
                    @foreach($columnObjects as $column_obj)
                        <td>{!! $column_obj->render($row) !!}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer py-4">
        {{ $paginator->render('grid_view::pagination') }}
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        $('[role="grid-view-filter-item"]').change(() => {
            $('#grid_view_filters_form').submit();
        })
    })
</script>
