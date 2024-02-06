@if (!empty($client->id))
    <div class="row mb-2 mx-2">
        <div class="col-md-12">
            <div class="col-md-4">
                <div style="padding-top: 31px">
                    {{--<button class="btn btn-danger send-event" data-url="{{ route('admin.clients.clearTests', ['client' => $client]) }}">
                        Очистить тесты
                    </button>--}}
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-2 mx-2">
        <div class="col-md-12">
            @include('admin.clients.form.test.test', ['text' => '/start', 'callback' => ''])
            @include('admin.clients.form.test.test', ['text' => 'stories', 'callback' => ''])
            @include('admin.clients.form.test.test', ['text' => 'disc', 'callback' => ''])
            @include('admin.clients.form.test.test', ['text' => 'meta', 'callback' => ''])
            @include('admin.clients.form.test.test', ['text' => '1', 'callback' => '["main.test"]'])
            @include('admin.clients.form.test.test', ['text' => 'asddsa', 'callback' => '["main.answerTest",1,1]'])
            @include('admin.clients.form.test.test', ['text' => 'asddsa', 'callback' => '["main.writeStories"]'])
            @include('admin.clients.form.test.test', ['text' => 'asddsa', 'callback' => '["main.writeMetamask"]'])
            @include('admin.clients.form.test.test', ['text' => 'asddsa', 'callback' => '["main.writeDiscord"]'])
        </div>

    </div>
@endif
