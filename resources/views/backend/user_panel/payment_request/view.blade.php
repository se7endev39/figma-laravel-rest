@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
		    <div class="card-header">
				<h4 class="card-title">{{ _lang('View Payment Request') }}</h4>
			</div>
			
			<div class="card-content">
				<div class="card-body">
				    <table class="table table-bordered">
						<tr><td>{{ _lang('Account') }}</td><td>{{ $paymentrequest->account->account_number }}</td></tr>
						<tr><td>{{ _lang('Amount') }}</td><td>{{ $paymentrequest->account->account_type->currency->name.' '.$paymentrequest->amount }}</td></tr>
						<tr><td>{{ _lang('Status') }}</td><td>{{ ucwords($paymentrequest->status) }}</td></tr>
						<tr><td>{{ _lang('Description') }}</td><td>{{ $paymentrequest->description }}</td></tr>
					</table>
				</div>
			</div>
	    </div>
	</div>
</div>
@endsection
