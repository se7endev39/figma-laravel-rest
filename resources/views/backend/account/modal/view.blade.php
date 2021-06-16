<table class="table table-bordered">
	<tr><td>{{ _lang('Account Number') }}</td><td>{{ $account->account_number }}</td></tr>
	<tr><td>{{ _lang('Account Owner') }}</td><td>{{ $account->owner->first_name.' '.$account->owner->last_name }}</td></tr>
	<tr><td>{{ _lang('Account Type') }}</td><td>{{ $account->account_type->account_type. ' (' . $account->account_type->currency->name.')' }}</td></tr>
	<tr>
		<td>{{ _lang('Status') }}</td>
		<td>
		   @if($account->status == 0)
				<span class="badge badge-danger">{{ _lang('InActive') }}</span>
			@elseif($account->status == 1)
				<span class="badge badge-success">{{ _lang('Active') }}</span>
			@endif
		</td>
	</tr>
	<tr><td>{{ _lang('Opening Balance') }}</td><td>{{ $account->opening_balance }}</td></tr>
	<tr><td>{{ _lang('Description') }}</td><td>{{ $account->description }}</td></tr>

	@if(Auth::user()->user_type == 'admin')
		<tr><td>{{ _lang('Created By') }}</td><td>{{ $account->created_user->first_name .' ('. $account->created_at .')' }}</td></tr>
		<tr><td>{{ _lang('Updated By') }}</td><td>{{ $account->updated_user->first_name .' ('. $account->updated_at .')'  }}</td></tr>
	@endif
</table>

