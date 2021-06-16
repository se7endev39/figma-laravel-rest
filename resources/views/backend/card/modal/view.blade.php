<table class="table table-bordered">
	<tr><td>{{ _lang('Account Owner') }}</td><td>{{ $card->owner->first_name.' '.$card->owner->last_name }}</td></tr>
	<tr><td>{{ _lang('Card Number') }}</td><td>{{ $card->card_number }}</td></tr>
	<tr><td>{{ _lang('Card Type') }}</td><td>{{ $card->card_type->card_type .' (' .$card->card_type->currency->name .')' }}</td></tr>
	<tr><td>{{ _lang('Status') }}</td><td>{{ $card->status == 1 ? _lang('Active') : _lang('Blocked') }}</td></tr>
	<tr><td>{{ _lang('Expiration Date') }}</td><td>{{ $card->expiration_date }}</td></tr>
	<tr><td>{{ _lang('CVV') }}</td><td>{{ $card->cvv }}</td></tr>
	<tr><td>{{ _lang('Note') }}</td><td>{{ $card->note }}</td></tr>
	@if(Auth::user()->user_type == 'admin')
		<tr><td>{{ _lang('Created By') }}</td><td>{{ $card->created_user->first_name .' ('. $card->created_at .')' }}</td></tr>
		<tr><td>{{ _lang('Updated By') }}</td><td>{{ $card->updated_user->first_name .' ('. $card->updated_at .')'  }}</td></tr>
	@endif
</table>