<table class="table table-bordered">
	<tr><td>{{ _lang('Name') }}</td><td>{{ $currency->name }}</td></tr>
	<tr><td>{{ _lang('Base Currency') }}</td><td>{{ $currency->base_currency == '1' ? _lang('Yes') : _lang('No') }}</td></tr>
	<tr><td>{{ _lang('Exchange Rate') }}</td><td>{{ $currency->exchange_rate }}</td></tr>
	<tr><td>{{ _lang('Status') }}</td><td>{{ $currency->status == '1' ? _lang('Active') : _lang('In-Active') }}</td></tr>		
</table>

