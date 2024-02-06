@extends('admin.layouts.auth')

@section('content')
	<div class="header bg-gradient-primary py-7">
		<div class="container">
			<div class="header-body text-center mb-7">
				<div class="row justify-content-center">
					<div class="col-lg-5 col-md-6">
						<h1 class="text-white">{{ config('app.name') }}</h1>
						<p class="text-white ">{{ __('admin.welcome') }}</p>
					</div>
				</div>
			</div>
		</div>
		<div class="separator separator-bottom separator-skew zindex-100">
			<svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
				<polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
			</svg>
		</div>
	</div>
	<div class="container mt--8 pb-5">
		<div class="row justify-content-center">
			<div class="col-lg-5 col-md-7">
				<div class="card bg-secondary shadow border-0">
					@if(config('auth.social.enabled'))
						<div class="card-header bg-transparent pb-5">
							<div class="text-muted text-center mt-2 mb-3"><small>{{ __('admin.auth.social') }}</small></div>
							<div class="btn-wrapper text-center">
								<a href="#" class="btn btn-neutral btn-icon">
									<span class="btn-inner--icon"><img src="{{ asset('admin/static/icons/github.svg') }}" alt=""></span>
									<span class="btn-inner--text">Github</span>
								</a>
								<a href="#" class="btn btn-neutral btn-icon">
									<span class="btn-inner--icon"><img src="{{ asset('admin/static/icons/google.svg') }}" alt=""></span>
									<span class="btn-inner--text">Google</span>
								</a>
							</div>
						</div>
					@endif

					<div class="card-body px-lg-5 py-lg-5">
						@if(!config('auth.social.enabled'))
							<img class="w-25 img-center img-fluid mb-4" src="{{ config('admin.logo') }}">
						@endif
						<div class="text-center text-muted mb-4">
							<small>{{ config('auth.social.enabled') ? __('admin.auth.orCredentials') : __('admin.auth.credentials') }}</small>
						</div>

						{{ BsForm::open(['route' => 'admin.auth.sign-in']) }}

						<div class="form-group{{ $errors->has('username') ? ' has-danger' : '' }} mb-3">
							<div class="input-group input-group-alternative">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="ni ni-circle-08"></i></span>
								</div>

								<input class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
								       placeholder="{{ __('admin.placeholders.username') }}"
								       name="username"
								       value="{{ old('username') }}"
								       autofocus
								       autocomplete="username">
							</div>
							@if ($errors->has('username'))
								<span class="invalid-feedback d-block" role="alert">{{ $errors->first('username') }}</span>
							@endif

						</div>
						<div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
							<div class="input-group input-group-alternative">
								<div class="input-group-prepend">
									<span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
								</div>
								<input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
								       name="password"
								       placeholder="{{ __('admin.placeholders.password') }}"
								       type="password"
								       autocomplete="password">
							</div>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback d-block" role="alert">{{ $errors->first('password') }}</span>
                            @endif
						</div>

                        {{ BsForm::checkbox('remember')
                            ->value(1)
                            ->checked(old('remember'))
                            ->label(__('admin.placeholders.remember'))
                            ->wrapperAttribute('class', 'custom-control custom-control-alternative custom-checkbox')
                            ->labelAttribute('class', 'text-muted')
                        }}

						{{ BsForm::submit(__('admin.auth.login'))
							->attribute('class', ' btn btn-primary my-4')
							->wrapperAttribute('class' , 'text-center')
						 }}
						{{ BsForm::close() }}
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
