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

