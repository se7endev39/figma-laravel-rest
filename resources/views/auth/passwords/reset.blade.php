@extends('layouts.login')

@section('content')
<div class="auth-section">
	<div class="curve-container">
		<div class="curve-top"></div>
		<div class="curve-bottom"></div>
	</div><!--//curve-container--> 
	<div class="container">
		<div class="auth-option over-curve text-center text-lg-right pt-4 mb-5">
			<a class="more-link" href="{{ url('login') }}">{{ _lang('Sign in') }} <strong>&rarr;</strong></a>              
		</div>
		<div class="single-col-max mx-auto over-curve">  
			
			<div class="site-logo mb-4 text-center"><a class="navbar-brand" href="index.html"><img class="logo-icon mr-2" src="{{ get_logo() }}" alt="logo"></a></div>   

			<div class="auth-wrapper px-5 mx-auto">
				<h2 class="auth-heading text-center mb-4">{{ _lang('Reset your password') }}</h2>
					
				<div class="auth-intro mb-4 text-center">{{ _lang("Enter your email address below. We'll email you a link to a page where you can easily create a new password.") }}</div>

				<div class="auth-form-container text-left">

					@if (session('status'))
                        <div class="alert alert-success">
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    <form method="POST" class="auth-form resetpass-form" action="{{ route('password.update') }}" autocomplete="off">
                        
						@csrf
						
						<input type="hidden" name="token" value="{{ $token }}">
						
						
						<div class="form-group row">
                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="{{ _lang('Email') }}" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ _lang('Password') }}" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="{{ _lang('Password Confirmation') }}" required autocomplete="new-password">
                            </div>
                        </div>
											
						<div class="text-center">
							<button type="submit" class="btn btn-primary btn-submit theme-btn mx-auto">{{ _lang('Reset Password') }}</button>
						</div>
					</form>
				</div><!--//auth-form-container-->
				
				<div class="auth-footer py-5 mt-5 text-center">
					<div class="copyright mb-2">
						{{ _lang('Copyright') }} &copy; <a class="theme-link" href="" target="_blank">{{ get_option('company_name') }}</a>
					</div>
					<div class="legal">
						<ul class="list-inline">
							<li class="list-inline-item"><a class="theme-link" href="#">{{ _lang('Privacy Policy') }}</a></li>
							<li class="list-inline-item">|</li>
							<li class="list-inline-item"><a class="theme-link" href="#">{{ _lang('Terms of Services') }}</a></li>
						</ul>
					</div>
				</div><!--//auth-footer-->
				
			</div><!--//auth-wrapper-->
		</div><!--//single-col-max-->
	</div><!--//container-->
</div><!--//auth-section-->
@endsection
