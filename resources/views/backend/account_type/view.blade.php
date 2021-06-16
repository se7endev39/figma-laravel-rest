@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="plus-circle"></i></div>
				<span>{{ _lang('View Account Type') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">{{ _lang('View Account Type') }}</h4>
				</div>
				
				<div class="card-content">
					<div class="card-body">
						
						<table class="table table-bordered">
							<tr><td>{{ _lang('Account Type') }}</td><td>{{ $accounttype->account_type }}</td></tr>
							<tr><td>{{ _lang('Currency') }}</td><td>{{ $accounttype->currency->name }}</td></tr>
							<tr><td>{{ _lang('Maintenance Fee (Annually)') }}</td><td>{{ $accounttype->maintenance_fee }}</td></tr>
							<tr><td>{{ _lang('Interest Rate') }}</td><td>{{ $accounttype->interest_rate }}</td></tr>
							<tr><td>{{ _lang('Interest Period') }}</td><td>{{ ucwords($accounttype->interest_period )}}</td></tr>
							<tr><td>{{ _lang('Payout Period') }}</td><td>{{ ucwords($accounttype->payout_period) }}</td></tr>
							<tr><td>{{ _lang('Auto Create') }}</td><td>{{ $accounttype->auto_create == 1 ? _lang('Yes') : _lang('No') }}</td></tr>
							<tr><td>{{ _lang('Description') }}</td><td>{{ $accounttype->description }}</td></tr>	
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection


