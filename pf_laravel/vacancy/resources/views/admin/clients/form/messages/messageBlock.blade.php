
@php
    /** @var \App\Domain\Client\Entities\ClientMessage $clientMessage */
    $clientMessage->text = str_replace(PHP_EOL, '<br>', $clientMessage->text);
    $name = trim($clientMessage->client->fullName) ?: $clientMessage->client->username;
@endphp
@if ($clientMessage->is_admin)
<div class='text-right bg-gradient-light card card-body col-md-6 offset-6 p--2' style='padding-top: 5px;padding-bottom: 5px'>
<div><strong class='bold'>Модератор</strong> ({{$clientMessage->created_at}})</div>
<div class='p--1'>{{$clientMessage->text}}</div>
</div>
@else

 <div class='text-left border-primary card card-footer col-md-6 p--2'  style='padding-top: 5px;padding-bottom: 5px'>
    <strong class='bold'>{{$name}}</strong>
    <div class='p--1'>{{$clientMessage->text}}</div>
    </div>
@endif
