@if ($message = Session::get('success'))
    <div class="alert alert-success">
        {{ $message }}
    </div>
@endif
@if ($message = Session::get('fail'))
    <div class="alert alert-warning">
        {{ $message }}
    </div>
@endif
