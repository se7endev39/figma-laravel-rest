<table class="table table-bordered">
	<tr><td>{{ _lang('Created') }}</td><td>{{ $transaction->created_at }}</td></tr>
	<tr><td>{{ _lang('Account') }}</td><td>{{ $transaction->account->account_number.' ('.$transaction->account->account_type->currency->name.')' }}</td></tr>
	<tr>
		<td>{{ _lang('Debit/Credit') }}</td>
		<td>
		    @if($transaction->dr_cr == 'cr')
				<span class="badge badge-success">{{ _lang('Credit') }}</span>
			@elseif($transaction->dr_cr == 'dr')
				<span class="badge badge-danger">{{ _lang('Dedit') }}</span>
			@endif
		</td>
	</tr>
	<tr><td>{{ _lang('Transaction Type') }}</td><td>{{ ucwords(str_replace('_',' ',$transaction->type)) }}</td></tr>
	<tr><td>{{ _lang('Amount') }}</td><td>{{ decimalPlace($transaction->amount) }}</td></tr>
	<tr><td>{{ _lang('Note') }}</td><td>{{ $transaction->note }}</td></tr>
	<tr>
		<td>{{ _lang('Status') }}</td>
		<td>
			@if($transaction->status == 'pending')
			<span class="badge badge-warning">{{ _lang('Pending') }}</span>
			@elseif($transaction->status == 'complete')
			<span class="badge badge-success">{{ _lang('Completed') }}</span>
			@elseif($transaction->status == 'cancel')
			<span class="badge badge-danger">{{ _lang('Canceled') }}</span>
			@endif
		</td>
	</tr>
	<tr><td>{{ _lang('User') }}</td><td>{{ $transaction->user->first_name.' '.$transaction->user->last_name }}</td></tr>
	<tr><td>{{ _lang('User Email') }}</td><td>{{ $transaction->user->email }}</td></tr>
	@if(Auth::user()->user_type == 'admin')
	<tr><td>{{ _lang('Created By') }}</td><td>{{ $transaction->created_user->first_name.' ('.$transaction->created_at.')' }}</td></tr>
	<tr><td>{{ _lang('Updated By') }}</td><td>{{ $transaction->updated_user->first_name.' ('.$transaction->updated_at.')' }}</td></tr>
	@endif
</table>

