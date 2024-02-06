<x-admin-fluid>
    <x-slot name="title">
        <h2 class="mb-0">{{ __('Users Management') }}</h2>
        <a class="btn btn-success" href="{{ route('admin.users.create') }}"> {{ __('Create New User') }}</a>
    </x-slot>

    <x-alert-success/>

    <div class="row mb-2">
        <div class="col-md-12 d-flex justify-content-between">
            <div>
                <form class="form-inline">
                    <div class="input-group mb-3">
                        <input name="filter[query]" value="{{ $filter['query'] ?? null }}" type="text" class="form-control" placeholder="{{ __('Search..') }}" aria-label="{{ __('Search..') }}" aria-describedby="search-submit">
                        <button class="btn btn-primary" type="submit" id="search-submit">{{ __('Filter') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <table class="table table-bordered">
        <tr>
            <th>{{ __('Id') }}</th>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Email') }}</th>
            <th>{{ __('Roles') }}</th>
            <th>{{ __('Verified email') }}</th>
            <th>{{ __('Action') }}</th>
        </tr>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if(!empty($user->getRoleNames()))
                        @foreach($user->getRoleNames() as $v)
                            <span class="badge bg-info">{{ $v }}</span>
                        @endforeach
                    @endif
                </td>
                <td>{{ $user->email_verified_at }}</td>
                <td>
                    <a class="btn btn-info me-1" href="{{ route('admin.users.show', $user->id) }}">{{ __('Show') }}</a>
                    <a class="btn btn-primary" href="{{ route('admin.users.edit', $user->id) }}">{{ __('Edit') }}</a>
                    @if (!app('impersonate')->isImpersonating())
                        <x-form method="post" :action="route('admin.users.impersonate', $user->id)" class="d-inline"
                                confirm="true">
                            <button class="btn btn-danger">{{ __('Impersonate') }}</button>
                        </x-form>
                    @endif
                    <x-form method="delete" :action="route('admin.users.destroy', $user->id)" class="d-inline"
                            confirm="true">
                        <button class="btn btn-danger">{{ __('Delete') }}</button>
                    </x-form>
                </td>
            </tr>
        @endforeach
    </table>


    {!! $users->appends(['filter' => $filter])->links() !!}

</x-admin-fluid>
