@extends('layout.candidate')
@section('content')
    <section class="content candidate">
        <div class="container">
            <div class="candidate__head">
                <h2>Лучшие разработчики для вашего проекта</h2>

                <p>Тщательно отобранные и проверенные кандидаты под любые задачи вашего проекта.</p>
            </div>
            <form id="candidate_filter_form" class="candidate__fillter" action="{{ route('candidate') }}" method="GET">
                <div class="candidate__fillter__col search">
                    <span class="candidate__fillter__title">@lang('site.search')</span>
                    <input
                        id="search"
                        name="search"
                        type="text"
                        value="{{ $search ?? '' }}"
                        placeholder="@lang('site.search')"
                        class="candidate__fillter__input">
                </div>
            </form>

            <div class="candidate__fillter__items">
                @foreach ($users as $user)
                    <div class="candidate__fillter__item">
                        <img class="candidate-photo" src="{{ $user->photo ? url('storage/'.$user->photo) : asset('/img/candidate_photo.jpg')}}" alt="">
                        <div class="candidate__fillter__item__title">{{ $user->{'first_name'} }} </div>
                        <div class="candidate__fillter__item__stack">{{ $user->{'specialization'.$locale} }} </div>
                        <div class="candidate__fillter__item__tag">
                            @if (!empty($user->skills))
                                @foreach (explode(',', $user->skills) as $skillId)
                                    <span>{{ $skills[$skillId]->{'name'.$locale} }}</span>
                                    @if ($loop->index === 2)
                                        <button class="tag-last" onclick="window.location.href = '{{ route('candidate.view', $user->slug) }}'">
                                            <div>...</div>
                                        </button>
                                        @break
                                    @endif
                                @endforeach
                            @else
                                <span>&nbsp;</span>
                            @endif
                        </div>
                        <a href="{{ route('candidate.view', $user->slug) }}" class="candidate__fillter__item__more btn">@lang('site.know_more')</a>
                    </div>
                @endforeach

            </div>

        </div>
    </section>
    <script type="text/javascript">
        function loadData() {
            document.getElementById('candidate_filter_form').submit();
        }
    </script>
@endsection
