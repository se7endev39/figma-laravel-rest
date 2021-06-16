@extends('layouts.login')

@section('content')
<div class="signin-section auth-section">
	<div class="curve-container">
		<div class="curve-top"></div>
		<div class="curve-bottom"></div>
	</div><!--//curve-container--> 
	<div class="container">
		<div class="auth-option over-curve text-center text-lg-right pt-4 mb-5">
		{{ _lang("Don't have an account?") }}<a class="ml-2 more-link" href="{{ url('register') }}">{{ _lang('Sign up now') }} <strong>&rarr;</strong></a>              
		</div>
		<div class="single-col-max mx-auto over-curve px-5">  
			
			<div class="site-logo mb-4 text-center"><a class="navbar-brand" href=""><img class="logo-icon mr-2" src="{{ get_logo() }}" alt="logo"></a></div>   

			<div class="auth-wrapper mx-auto login_form">
				<h2 class="auth-heading text-center mb-4">{{ _lang('Login to ')}}<span class="your-acount">{{_lang('your account') }}</span></h2>

				<div class="auth-form-container text-left mx-auto"> 
					<form method="POST" class="auth-form signup-form validate" action="{{ route('login') }}">
                        @csrf
						<div class="form-group email">
							<label class="sr-only" for="signin-email">{{ _lang('Email') }}</label>
							
							<input id="signin-email" class="input-text" name="email" type="email" value="{{ old('email') }}" class="form-control signin-email{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ _lang('Email') }}" required="required">
							<span class="focus-border"></span>
							<i class="fa fa-envelope"></i>
							
							@if ($errors->has('email'))
								<span class="invalid-feedback">
									<strong>{{ $errors->first('email') }}</strong>
								</span>
							@endif
							
						</div><!--//form-group-->
						<div class="form-group password">
							<label class="sr-only" for="signin-password">{{ _lang('Password') }}</label>
							<input id="signin-password" class="input-text" name="password" type="password" class="form-control signin-password{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ _lang('Password') }}" required="required">
							<i class="fa fa-lock"></i>
							<span class="focus-border"></span>
							@if ($errors->has('password'))
								<span class="invalid-feedback">
									<strong>{{ $errors->first('password') }}</strong>
								</span>
							@endif
							
							<div class="extra mt-2 position-relative">
								<div class="checkbox remember">
									<label>
										<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ _lang('Remember me') }}
									</label>
								</div><!--//check-box-->
								<div class="forgotten-password">
									<a href="{{ route('password.request') }}">{{ _lang('Forgotten password?') }}</a>
								</div>
							</div><!--//extra-->
						</div><!--//form-group-->
						<div class="text-center">
							<button type="submit" class="btn btn-primary btn-gradient btn-submit theme-btn mx-auto">{{ _lang('Login') }}</button>
						</div>
					</form>
				</div><!--//auth-form-container-->
				
				
				<div class="social-auth text-center mx-auto mt-2">                        
					<ul class="social-buttons list-unstyled">
						@if(get_option('allow_singup','yes') == 'yes')
							<li class="mb-3">
								<a href="{{ url('register') }}" class="btn btn-social btn-block">
									<span class="btn-text">{{ _lang('Open An Account') }}</span>
								</a>
							</li>
						@endif
						<!--<li class="mb-3"><a href="#" class="btn btn-social btn-block"><span class="icon-holder"><span class="icon-holder"><img src="assets/images/social/twitter-logo.svg" alt=""></span></span><span class="btn-text">Login with Twitter</span></a></li>
						<li class="mb-3"><a href="#" class="btn btn-social btn-block"><span class="icon-holder"><span class="icon-holder"><img src="assets/images/social/github-logo.svg" alt=""></span></span><span class="btn-text">Login with Github</span></a></li>-->
					</ul>
				</div><!--//social-auth-->
				
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
</div><!--//signin-section-->
	
@endsection
