<ul class="navbar-nav ms-auto">
    @canany(['user-index','role-index'])
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle"
               href="#"
               data-bs-toggle="dropdown"
               role="button"
               aria-expanded="false"
            >
                {{ __('Manage') }} <span class="mdi mdi-caret-down"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                @can('user-index')
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.users.index') }}">{{ __('Users') }}</a>
                    </li>
                @endcan
                @can('role-index')
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.roles.index') }}">{{ __('Roles') }}</a>
                    </li>
                @endcan
            </ul>
        </li>
    @endcan
    @auth
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarAccountDD" role="button"
               data-bs-toggle="dropdown" aria-expanded="false">
                {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarAccountDD">
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}">
                        {{ __('Logout') }}
                    </a>
                </li>
            </ul>
        </li>
    @else
        @if(config('app.enabled.auth.register'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
        </li>
    @endauth
</ul>
