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
				<h4 class="auth-heading text-center mb-4">{{ _lang('Verify Your Email Address') }}</h4>
				
				@if (session('resent'))
					<div class="alert alert-success" role="alert">
						{{ _lang('A fresh verification link has been sent to your email address.') }}
					</div>
				@endif
					
				<div class="auth-intro mb-4 text-center">{{ _lang('Before proceeding, please check your email for a verification link.') }}</div>
				
				<div class="auth-intro mb-4 text-center">{{ _lang('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ _lang('click here to request another') }}</a>.</div>
	
				
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
