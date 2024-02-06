@extends('web.layouts.main')
<?php
/**
 * @var \App\Domain\TourClub\Entities\TourClub $tourClub
 */

?>
@section('content')

    <div class="tour-club-landing">
        <div class="header">
            <div class="avatar">
                <a href="https://vk.com/ikartk" target="_blank"
                >
                    <img
                            src="https://sun9-north.userapi.com/sun9-79/s/v1/if2/yk9Ksq15iEl8eQDM-BxhTHmHjTge7L2-oUU3zKosC_SOdU2JbP8uHqhxttLJLWZeyPJOClQH8XUrAdtpFXydzf18.jpg?size=1350x1350&quality=96&type=album"
                            alt="{{ $tourClub->title }}"
                    >
                </a>
            </div>
            <h1>{{ $tourClub->title }}</h1>
        </div>
        <div class="content">
            @if($tourClub->futureEvents->count() > 0)
                <h3>Наши мероприятия:</h3>
                <div class="events">
                    @foreach($tourClub->futureEvents as $event)
                        <div class="event-block">
                            <a class="button-link" href="https://t.me/turstudbot" target="_blank">
                                <div>
                                    <div class="title">
                                        {{$event->title}}
                                    </div>
                                    <div class="subtitle">
                                        {{ $event->start_at->format('d.m.Y H:i') }}
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="content">
            <div class="about">
                <h3>Чтобы вступить в ТК надо подписаться:</h3>
                🔸группа в ВК <a href="https://vk.com/ikartk" target="_blank">https://vk.com/ikartk</a> (все новости о мероприятиях) <br>
                🔸бот в телеграм <a href="https://t.me/turstudbot" target="_blank">https://t.me/turstudbot</a> (регистрация к нам в клуб) <br>
                🔸беседа начинающих туристов <a href="https://vk.me/join/AJQ1d2FIsiKV/zxHNGdXY9_h" target="_blank">https://vk.me/join/AJQ1d2FIsiKV/zxHNGdXY9_h</a> <br>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    {{--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.3.7/js/swiper.min.js"></script>--}}
@endpush