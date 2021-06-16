@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="user"></i></div>
				<span>{{ _lang('User Documents') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card">

				<div class="card-body">
				
					<h4 class="card-title"><span class="panel-title">{{ _lang('User Documents') }}</span></h4>

					<table class="table table-bordered data-table">
						<thead>
						<tr>
							<th>{{ _lang('First Name') }}</th>
							<th>{{ _lang('Last Name') }}</th>
							<th>{{ _lang('Email') }}</th>
							<th class="text-center">{{ _lang('Account Status') }}</th>
							<th class="text-center">{{ _lang('Total Document') }}</th>
							<th class="text-center">{{ _lang('View') }}</th>
						</tr>
						</thead>
						<tbody>
						
						@foreach($users as $user)
							<tr id="row_{{ $user->id }}">
								<td class='first_name'>{{ $user->first_name }}</td>
								<td class='last_name'>{{ $user->last_name }}</td>
								<td class='email'>{{ $user->email }}</td>																	
								<td class='text-center'>{!! $user->account_status == 'Verified' ? status(_lang('Verified'),'success') : status(_lang('Unverified'),'danger') !!}</td>					
								<td class='text-center'>{{ $user->documents->count() }}</td>					
								<td class="text-center">
									<a href="{{ url('admin/users/documents/'.$user->id) }}" class="btn btn-info btn-xs">{{ _lang('View Documents') }}</a>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection


