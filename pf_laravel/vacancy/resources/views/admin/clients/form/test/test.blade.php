<div class="row">
    <div class="col-5">
        {{
            BsForm::text('tester-text')->attribute([
                'name' => '',
                'class' => 'text form-control tester-text',
                ])
                ->value($text)
        }}
    </div>
    <div class="col-5">
        {{
            BsForm::textarea('tester-callback')->attribute([
                'name' => '',
                'class' => 'callback form-control tester-callback',
                ])->rows(1)->value($callback)
        }}
    </div>
    <div class="col-1">
        <button class="btn btn-primary send-message" data-url="{{ route('admin.clientMessage.test', ['client' => $client]) }}">
            Отправить
        </button>
    </div>
</div>
