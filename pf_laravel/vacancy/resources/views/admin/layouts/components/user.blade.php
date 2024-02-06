<?php
/** @var \App\Domain\User\Entities\User $user */
$user = Auth::user();

?>
<ul class="navbar-nav align-items-center ml-auto ml-md-0">
    <li class="nav-item dropdown">
        <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                    <img src="{{ $user->avatar->url ?? asset('admin/static/images/avatar.png') }}" alt="avatar">
                  </span>
                <div class="media-body ml-2 d-none d-lg-block">
                    <span class="mb-0 text-sm  font-weight-bold">{{ $user->getFullName() }}</span>
                </div>
            </div>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-header noti-title">
                <h6 class="text-overflow m-0">{{ __('admin.menu.menu') }}</h6>
            </div>
            <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">
                <i class="ni ni-single-02 text-primary"></i>
                <span>{{ __('admin.nav.profile') }}</span>
            </a>
            {!! Form::open(['route' => 'admin.auth.sign-out']) !!}
                <button class="btn btn-link dropdown-item">
                    <i class="fa fa-sign-out-alt text-danger" aria-hidden="true"></i>
                    <span>{{ __('admin.actions.sign-out') }}</span>
                </button>
            {!! Form::close()  !!}
        </div>
    </li>
</ul>
