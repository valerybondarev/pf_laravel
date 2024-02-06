<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                @if ($logo = config('admin.logo'))
                    <img src="{{ asset($logo) }}" class="navbar-brand-img" alt="{{ config('app.name') }}">
                @endif
            </a>
            <div class="ml-auto">
                <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-inner">
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                @foreach(config('admin.sidebar') as $section)
                    @include('admin.layouts.components.sidebar.section', ['section' => $section])
                @endforeach
            </div>
        </div>
    </div>
</nav>
