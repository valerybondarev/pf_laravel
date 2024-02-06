<ul class="navbar-nav me-auto mb-2 mb-lg-0">
    @auth
        @can('session-index')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.sessions.index') }}">{{ __('Sessions') }}</a>
            </li>
        @endcan
    @endauth
</ul>
