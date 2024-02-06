@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div {{ $attributes->merge(['class' => 'card overflow-x']) }}>
                    @if (isset($title))
                        <div class="card-header d-flex justify-content-between align-items-center">{{ $title }}</div>
                    @endif
                    <div class="card-body">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($modals))
        {{ $modals }}
    @endif
@endsection
