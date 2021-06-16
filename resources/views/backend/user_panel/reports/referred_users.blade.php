@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="bar-chart"></i></div>
				<span>{{ _lang('My Referred Users') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card panel-default">

				<span class="d-none panel-title">{{ _lang('My Referred Users') }}</span>

				<div class="card-body">

					<div class="report-header mt-2">
						<h5>{{ _lang('My Referred Users') }}</h5>
					</div>

					<table class="table table-striped report-table">
						<thead>
							<th>{{ _lang('ID') }}</th>
							<th>{{ _lang('Created') }}</th>
							<th>{{ _lang('Account Type') }}</th>
							<th>{{ _lang('Name') }}</th>
							<th>{{ _lang('Email') }}</th>
						</thead>
						<tbody>
							@if( isset($report_data) )
								@foreach($report_data as $user)
								<tr>
									<td>{{ $user->id }}</td>
									<td>{{ date('d M, Y h:iA',strtotime($user->created_at)) }}</td>
									<td>{{ ucwords($user->account_type) }}</td>
									<td>{{ $user->first_name.' '.$user->last_name }}</td>
									<td>{{ $user->email }}</td>
								</tr>
								@endforeach
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('js-script')
<script>
	document.title = $(".panel-title").html();
</script>
@endsection


