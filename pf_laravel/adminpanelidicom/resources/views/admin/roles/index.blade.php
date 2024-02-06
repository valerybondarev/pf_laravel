<x-admin-fluid>
    @slot('title')
        <h2 class="mb-0">{{ __('Role Management') }}</h2>
        <a class="btn btn-success" href="{{ route('admin.roles.create') }}"> {{ __('Create New Role') }}</a>
    @endslot

    <x-alert-success/>

    <table class="table table-bordered">
        <tr>
            <th>{{ __('Id') }}</th>
            <th>{{ __('Name') }}</th>
            <td>{{ __('Permissions') }}</td>
            <th>{{ __('Action') }}</th>
        </tr>
        @foreach ($roles as $role)
            <tr>
                <td>{{ $role->id }}</td>
                <td>{{ $role->name }}</td>
                <td>
                    @foreach($role->permissions as $permission)
                        <span class="badge bg-info">{{ $permission->name }},</span>
                    @endforeach
                </td>
                <td>
                    @can('role-edit')
                        <a class="btn btn-primary" href="{{ route('admin.roles.edit',$role->id) }}">{{ __('Edit') }}</a>
                    @endcan
                    @can('role-delete')
                        <x-form method="delete" :action="route('admin.roles.destroy', $role->id)" class="d-inline">
                            <button class="btn btn-danger">{{ __('Delete') }}</button>
                        </x-form>
                    @endcan
                </td>
            </tr>
        @endforeach
    </table>

    {!! $roles->render() !!}
</x-admin-fluid>
