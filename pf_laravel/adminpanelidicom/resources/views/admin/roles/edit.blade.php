<x-admin-fluid>
    <x-slot name="title">
        <h2 class="mb-0">{{ __('Update Role') }}</h2>
        <a class="btn btn-success" href="{{ route('admin.roles.index') }}"> {{ __('Back') }}</a>
    </x-slot>

    <x-error :errors="$errors"/>

    <x-form :action="route('admin.roles.update', $role->id)" method="patch">
        <div class="form-floating mb-3">
            <input name="name" value="{{ old('name', $role->name) }}" class="form-control" id="floatingInput"
                   placeholder="{{ __('Name') }}">
            <label for="floatingInput">{{ __('Name') }}</label>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                {{ __('Permission') }}
            </div>
            <div class="card-body">
                @foreach($permissions as $permission)
                    <div class="form-check">
                        <input name="permissions[]" value="{{ $permission->id }}" class="form-check-input"
                               type="checkbox"
                               id="permission_{{ $permission->id }}"{{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? ' checked' : '' }}>
                        <label class="form-check-label" for="permission_{{ $permission->id }}">
                            {{ $permission->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
        </div>
    </x-form>
</x-admin-fluid>
