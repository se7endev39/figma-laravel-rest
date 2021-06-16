@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="user"></i></div>
				<span>{{ _lang('View User Details') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card">
			
				<div class="card-body">
					<h4 class="card-title panel-title">{{ _lang('View User Details') }}</h4>

					<table class="table table-bordered">
						<tr><td colspan="2"  class="text-center"><img class="img-lg thumbnail" src="{{ profile_picture() }}"></td></tr>
						<tr><td>{{ _lang('First Name') }}</td><td>{{ $user->first_name }}</td></tr>
						<tr><td>{{ _lang('Last Name') }}</td><td>{{ $user->last_name }}</td></tr>
						<tr><td>{{ _lang('Email') }}</td><td>{{ $user->email }}</td></tr>
						<tr><td>{{ _lang('Phone') }}</td><td>{{ $user->phone }}</td></tr>	
						<tr><td>{{ _lang('User Type') }}</td><td>{{ ucwords($user->user_type) }}</td></tr>		
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection


