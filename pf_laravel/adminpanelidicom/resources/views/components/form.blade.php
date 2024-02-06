@props([
    'method' => 'GET',
    'confirm' => false,
])
@php
    $method = strtoupper($method);
@endphp
<form {{ $attributes }} method="{{ in_array($method, ['PUT','DELETE','PATCH']) ? 'POST' : $method }}"{!! $confirm ? ' onSubmit="return confirm(\''.__('Are you sure?').'\');"' : '' !!}>
    @if (in_array($method, ['PUT','DELETE','PATCH']))
        <input type="hidden" name="_method" value="{{ $method }}">
    @endif
    @csrf
    {{ $slot }}
</form>
