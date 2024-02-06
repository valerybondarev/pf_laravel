<x-admin-fluid>
    @slot('title')
        <h2 class="mb-0">{{ __('Sessions') }}</h2>
    @endslot

    <x-alert-success/>

    <div class="row mb-2">
        <div class="col-md-12 d-flex justify-content-between">
            <div>
                <form class="form-inline">
                    <div class="input-group mb-3">
                        <input name="filter[query]" value="{{ $filter['query'] ?? null }}" type="text"
                               class="form-control" placeholder="{{ __('Search..') }}" aria-label="{{ __('Search..') }}"
                               aria-describedby="search-submit">
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
            <th>{{ __('Device ID') }}</th>
            <th>{{ __('Nickname') }}</th>
            <th>{{ __('Created at') }}</th>
            <th>{{ __('Updated at') }}</th>
            <th>{{ __('Action') }}</th>
        </tr>
        @foreach ($sessions as $session)
            <tr>
                <td>{{ $session->id }}</td>
                <td>{{ $session->name }}</td>
                <td>{{ $session->device_id }}</td>
                <td>{{ $session->nickname }}</td>
                <td>{{ $session->created_at }}</td>
                <td>{{ $session->updated_at }}</td>
                <td>
                    @can('session-view')
                        <a class="btn btn-primary"
                           href="{{ route('admin.sessions.show',$session->id) }}">{{ __('Show') }}</a>
                    @endcan
                </td>
            </tr>
        @endforeach
    </table>

    {!! $sessions->appends(['filter' => $filter])->links() !!}
</x-admin-fluid>
