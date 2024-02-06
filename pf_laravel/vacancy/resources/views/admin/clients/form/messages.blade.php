<div class="row mb-2 mx-2">
    <div class="col-md-12">
        <div id="messages" style="max-height: 400px;overflow-x: auto">
            <?php
                /** @var \App\Domain\Client\Entities\Client $client */
            ?>
                @foreach ($client->clientMessages as $clientMessage)
                    @include('admin.clients.form.messages.messageBlock')
                @endforeach
        </div>
    </div>
    @if (!empty($client->id))
        <div class="col-md-12">
            <div class="row">
                <div class="col-10">

                    {{
                        BsForm::textarea('message-text')->attribute([
                            'name' => '',
                            'id' => 'text-message',
                            'data-url' => route('admin.clientMessage.send', ['client' => $client]),
                            ])->rows(2)
                    }}

                </div>
                <div class="col-1">
                    <button id="send-message" class="btn btn-primary" disabled>
                        Отправить
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
