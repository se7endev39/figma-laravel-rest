@extends('layouts.app')

@section('content')
<div class="container-fluid mt-10">
	<div class="row">
		<div class="col-lg-6 offset-lg-3">
			<div class="card">
				<div class="card-body">
					@if(Session::has('success') && session('paid') == 1)
						<div class="alert alert-success">
						   <button type="button" class="close" data-dismiss="alert">&times;</button>
						   <strong>{{ session('success').' '._lang('You will be redirect after 10 seconds') }}</strong>
						</div>	

						<script>
						 setTimeout(function(){
							window.location.href = "{{ session('success_url') }}";
						 }, 10000);
						</script>
					@endif
				</div>
		    </div>
		</div>
	</div>
</div>
@endsection
