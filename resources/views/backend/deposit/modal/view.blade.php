<table class="table table-bordered">
	<tr><td>{{ _lang('Created') }}</td><td>{{ $deposit->created_at }}</td></tr>
	<tr><td>{{ _lang('Account') }}</td><td>{{ $deposit->account->account_number.' ('.$deposit->account->account_type->currency->name.')' }}</td></tr>
	<tr><td>{{ _lang('Deposit Method') }}</td><td>{{ $deposit->method }}</td></tr>
	<tr><td>{{ _lang('Type') }}</td><td>{{ ucwords(str_replace('_',' ',$deposit->type)) }}</td></tr>
	<tr><td>{{ _lang('Amount') }}</td><td>{{ $deposit->amount }}</td></tr>
	<tr><td>{{ _lang('Note') }}</td><td>{{ $deposit->note }}</td></tr>
	<tr>
		<td>{{ _lang('Status') }}</td>
		<td>
		   @if($deposit->status == 0)
				<span class="badge badge-warning">{{ _lang('Pending') }}</span>
			@elseif($deposit->status == 1)
				<span class="badge badge-success">{{ _lang('Completed') }}</span>
			@elseif($deposit->status == 2)
				<span class="badge badge-danger">{{ _lang('Canceled') }}</span>
			@endif
		</td>
	</tr>
	<tr><td>{{ _lang('User') }}</td><td>{{ $deposit->user->first_name.' '.$deposit->user->last_name }}</td></tr>
	<tr><td>{{ _lang('User Email') }}</td><td>{{ $deposit->user->email }}</td></tr>
	@if(Auth::user()->user_type == 'admin')
		<tr><td>{{ _lang('Created By') }}</td><td>{{ $deposit->transaction->created_user->first_name.' ('.$deposit->transaction->created_at.')' }}</td></tr>
		<tr><td>{{ _lang('Updated By') }}</td><td>{{ $deposit->transaction->updated_user->first_name.' ('.$deposit->transaction->updated_at.')' }}</td></tr>
	@endif
</table>

