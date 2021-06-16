<table class="table table-bordered">
	<tr><td>{{ _lang('Card Number') }}</td><td>{{ $cardtransaction->card_id }}</td></tr>
			<tr><td>{{ _lang('Dr Cr') }}</td><td>{{ $cardtransaction->dr_cr }}</td></tr>
			<tr><td>{{ _lang('Type') }}</td><td>{{ $cardtransaction->type }}</td></tr>
			<tr><td>{{ _lang('Amount') }}</td><td>{{ $cardtransaction->amount }}</td></tr>
			<tr><td>{{ _lang('Note') }}</td><td>{{ $cardtransaction->note }}</td></tr>
			<tr><td>{{ _lang('Transaction Id') }}</td><td>{{ $cardtransaction->transaction_id }}</td></tr>
			<tr><td>{{ _lang('Created By') }}</td><td>{{ $cardtransaction->created_by }}</td></tr>
			<tr><td>{{ _lang('Updated By') }}</td><td>{{ $cardtransaction->updated_by }}</td></tr>
			
</table>

