{{ Form::open(['url' => $url, 'method' => 'delete']) }}
<button class="grid__icon table-action table-action-delete" data-toggle="tooltip" data-placement="top"
        title="{{ __('admin.actions.destroy') }}">
    <i class="fas fa-trash"></i>
</button>
{{ Form::close() }}

