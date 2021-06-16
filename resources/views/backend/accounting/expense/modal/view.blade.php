<table class="table table-bordered">
	<tr><td>{{ _lang('Date') }}</td><td>{{ $financetransaction->trans_date }}</td></tr>
	<tr>
		<td>{{ _lang('Income Category') }}</td><td>{{ $financetransaction->category->name }}</td>
	</tr>
	<tr><td>{{ _lang('Amount') }}</td><td>{{ get_base_currency().' '.$financetransaction->amount }}</td></tr>
	<tr><td>{{ _lang('Reference') }}</td><td>{{ $financetransaction->reference }}</td></tr>
	<tr><td>{{ _lang('Note') }}</td><td>{{ $financetransaction->note }}</td></tr>
	<tr>
		<td>{{ _lang('Attachment') }}</td>
		<td>
			{!! $financetransaction->attachment == "" ? '' : '<a href="'. asset('uploads/transactions/'.$financetransaction->attachment) .'" target="_blank">'._lang('Download').'</a>' !!}
		</td>
	</tr>
</table>

