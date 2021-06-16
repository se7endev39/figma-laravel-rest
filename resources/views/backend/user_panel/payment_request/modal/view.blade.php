<table class="table table-bordered">
	<tr><td>{{ _lang('Account') }}</td><td>{{ $paymentrequest->account->account_number }}</td></tr>
	<tr><td>{{ _lang('Amount') }}</td><td>{{ $paymentrequest->account->account_type->currency->name.' '.$paymentrequest->amount }}</td></tr>
	<tr><td>{{ _lang('Status') }}</td><td>{{ ucwords($paymentrequest->status) }}</td></tr>
	<tr><td>{{ _lang('Description') }}</td><td>{{ $paymentrequest->description }}</td></tr>
</table>