<?php
$messages = collect();

if (session()->has('error')) {
    $messages->push(['class' => 'danger', 'text' => session()->get('error')]);
}

if (session()->has('success')) {
    $messages->push(['class' => 'success', 'text' => session()->get('success')]);
}

?>

<div class="col-md-6">
    @foreach($messages as $message)
        <div class="alert alert-{{ $message['class'] }} alert-dismissible fade show" role="alert">
            {{ $message['text'] }}
            <br>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endforeach

    @if ($errors->any())
            <div class="accordion" id="errorsContainer">
                <div class="card bg-danger text-white">
                    <div class="card-header bg-danger" id="errorsHeader" data-toggle="collapse" data-target="#errors" aria-expanded="false" aria-controls="errors">
                        <h5 class="mb-0 text-white">{{ __('admin.errors.exists') }}</h5>
                    </div>
                    <div id="errors" class="collapse" aria-labelledby="errorsHeader" data-parent="#errorsContainer">
                        <div class="card-body">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li><small>{{ $error }}</small></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
    @endif
</div>

