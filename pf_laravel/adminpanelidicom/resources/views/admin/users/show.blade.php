<x-admin-fluid>
    <x-slot name="title">
        <h2 class="mb-0">{{ __('Show User') }}</h2>
        <div>
            <a class="btn btn-info" href="{{ route('admin.users.index') }}"> {{ __('Back') }}</a>
            <a href="{{ route('admin.users.edit', [$user->id]) }}" class="btn btn-primary">{{ __('Edit') }}</a>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                {{ $user->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Email:</strong>
                {{ $user->email }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Roles:</strong>
                @if(!empty($user->getRoleNames()))
                    @foreach($user->getRoleNames() as $v)
                        <label class="badge badge-success">{{ $v }}</label>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</x-admin-fluid>
