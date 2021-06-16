<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ get_option('site_title','Secure PaymentZ') }}</title>

    <meta name="og:title" content="Online Banking Software"/>
    <meta name="og:type" content="website"/>
    <meta name="og:url" content="{{ url('') }}"/>
    <meta name="og:image" content="{{ get_logo() }}"/>
    <meta name="og:site_name" content="{{ get_option('site_title','Secure PaymentZ') }}"/>
    <meta name="og:description" content="This is the perfect system for business that want to have a banking system, administrator, user accounts, Mobile Banking, affiliate systems, or financial institutions, as well as multi-level businesses."/>

    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />
    
	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,700&display=swap" rel="stylesheet">
	
	<!-- FontAwesome JS-->
	<script defer src="{{ asset('login_assets/fontawesome/js/all.min.js') }}"></script>
	
	<!-- Plugins CSS -->
	<link rel="stylesheet" href="{{ asset('login_assets/dripicons/webfont/webfont.css') }}">
	
    <!-- Styles -->
	<link href="{{ asset('css/intlTelInput.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('login_assets/css/theme.css?v=1.0') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @yield('content')     
    </div>
	
	<!-- Scripts -->
	<script src="{{ asset('login_assets/js/jquery-1.11.3.min.js') }}"></script>
    <script src="{{ asset('login_assets/js/app.js') }}"></script>
	<script src="{{ asset('js/intlTelInput-jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
    
	<script type="text/javascript">
		$(".phone").intlTelInput({
			nationalMode: false,
			//separateDialCode: true,
		});  

        $(".validate").validate({
            submitHandler: function(form) {
                form.submit();
            },invalidHandler: function(form, validator) {},
              errorPlacement: function(error, element) {}
        });    

        $('.select2').select2();

        $('.datepicker').datepicker();
	</script>
	@yield('js-script')
  </body>
</html>
