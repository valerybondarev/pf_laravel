@props([
    'errors' => []
])
@if (count($errors))
    <div class="alert alert-danger">
        <strong>{{ __('Whoops!') }}</strong> {{ __('There were some problems with your input.') }}<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
