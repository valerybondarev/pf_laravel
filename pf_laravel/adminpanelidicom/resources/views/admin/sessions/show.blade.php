<x-admin-fluid>
    @slot('title')
        <h2 class="mb-0">{{ __('Session') }}: {{ $session->name }}</h2>
    @endslot

    <x-alert-success/>
    <h3>{{ __('State') }}</h3>

    <table class="table table-bordered table-striped table-hover">
        <thead>
        <tr>
            <th>{{ __('Step') }}</th>
            <th>{{ __('File') }}</th>
            <th>{{ __('Status') }}</th>
            <th>{{ __('Exception') }}</th>
        </tr>
        </thead>
        @foreach($steps as $step)
            <tr>
                <td>{{ $step->step }}</td>
                <td>
                    <a href="{{ URL::temporarySignedRoute('files.download', 3600, [$step->file->id]) }}">
                        {{ $step->file->original }}
                    </a>
                </td>
                <td>{{ $step->status }}</td>
                <td>{{ $step->exception }}</td>
            </tr>
        @endforeach
    </table>

    <h3>{{ __('Status flow') }}</h3>
    <table class="table table-bordered table-striped table-hover">
        <thead>
        <tr>
            <th></th>
            @foreach($statuses as $status)
                <th>{{ $status }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @for($i = 0; $i <= 16; $i++)
            <tr>
                <th>{{ $i }}</th>
                @foreach($statuses as $status)
                    @if(!empty($data[$i][$status]))
                        <th>
                            <a href="{{ URL::temporarySignedRoute('files.download', 3600, [$data[$i][$status]['result']['id']]) }}">
                                {{ $data[$i][$status]['result']['original'] }}
                            </a>
                            <x-form method="post" :action="route('admin.sessions.change', $session->id)" class="d-inline"
                                    enctype="multipart/form-data"
                                    confirm="true">
                                <input type="hidden" name="step" value="{{ $i }}">
                                <input type="hidden" name="status" value="{{ $status }}">
                                <input type="file" name="file" required>
                                <button class="btn btn-danger">{{ __('Upload') }}</button>
                            </x-form>
                        </th>
                    @else
                        <th class="bg-warning">-</th>
                    @endif
                @endforeach
            </tr>
        @endfor
        </tbody>
    </table>
</x-admin-fluid>
