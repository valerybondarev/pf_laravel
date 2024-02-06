<x-admin-fluid>
    <x-slot name="title">
        <h2 class="mb-0">{{ __('Edit User') }}</h2>
        <a class="btn btn-primary" href="{{ route('admin.users.index') }}"> Back</a>
    </x-slot>

    <x-error :errors="$errors"/>

    <x-form :action="route('admin.users.update', $user->id)" method="patch">
        <div class="form-floating mb-3">
            <input name="name" value="{{ old('name', $user->name) }}" class="form-control" id="floatingInput"
                   placeholder="{{ __('Name') }}">
            <label for="floatingInput">{{ __('Name') }}</label>
        </div>
        <div class="form-floating mb-3">
            <input name="email" value="{{ old('email', $user->email) }}" class="form-control" id="floatingInput"
                   placeholder="{{ __('Email') }}">
            <label for="floatingInput">{{ __('Email') }}</label>
        </div>
        <div class="form-floating mb-3">
            <input name="email_verified_at" value="{{ old('email_verified_at', $user->email_verified_at) }}"
                   class="form-control" id="floatingInput" placeholder="{{ __('Email verified at') }}">
            <label for="floatingInput">{{ __('Email verified at') }}</label>
        </div>
        <div class="form-floating mb-3">
            <input name="password" type="password" class="form-control" id="floatingInput"
                   placeholder="{{ __('Password') }}">
            <label for="floatingInput">{{ __('Password') }}</label>
        </div>
        <div class="form-floating mb-3">
            <input name="password_confirmation" type="password" class="form-control" id="floatingInput"
                   placeholder="{{ __('Confirm Password') }}">
            <label for="floatingInput">{{ __('Confirm Password') }}</label>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                {{ __('Roles') }}
            </div>
            <div class="card-body">
                @foreach($roles as $role)
                    <div class="form-check">
                        <input name="roles[]" value="{{ $role->name }}" class="form-check-input" type="checkbox"
                               id="role_{{ $role->name }}"{{ in_array($role->name, old('roles', $user->roles->pluck('name')->toArray())) ? ' checked' : '' }}>
                        <label class="form-check-label" for="role_{{ $role->name }}">
                            {{ $role->name }}
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
