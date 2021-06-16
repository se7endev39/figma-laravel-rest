<style>
	.modal-dialog{
		max-width: 1140px;
	}
</style>	

@if($transaction->status == 'pending')
	<div class="row mb-3">
		<div class="col-md-12">
			<a class="btn btn-success btn-sm" href="{{ url('admin/transfer/action/' . $transaction->id . '/approve') }}"><i class="far fa-check-square"></i>&nbsp;{{ _lang('Mark as Approved') }}</a>

			<a class="btn btn-danger btn-sm float-right" href="{{ url('admin/transfer/action/' . $transaction->id . '/reject') }}"><i class="far fa-times-circle"></i>&nbsp;{{ _lang('Mark as Rejected') }}</a>
		</div>
	</div>	
@endif

@if($transaction->type == 'wire_transfer')
	<div class="row">
	<div class="col-md-5">
@endif


@if($transaction->type == 'transfer' || $transaction->type == 'card_transfer')
	<div class="row">
	<div class="col-md-6">
@endif
    
    @php $currency1 = $transaction->account->account_type->currency->name; @endphp
	<table class="table table-striped">
		<tr><td colspan="2"><b>{{ _lang('Debit Account Details') }}</b></td></tr>
		<tr><td>{{ _lang('Created') }}</td><td>{{ $transaction->created_at }}</td></tr>
		<tr><td>{{ _lang('Account') }}</td><td>{{ $transaction->account->account_number.' ('.$currency1.')' }}</td></tr>
		<tr>
			<td>{{ _lang('Debit/Credit') }}</td>
			<td>
			    @if($transaction->dr_cr == 'cr')
					<span class="badge badge-success">{{ _lang('Credit') }}</span>
				@elseif($transaction->dr_cr == 'dr')
					<span class="badge badge-danger">{{ _lang('Debit') }}</span>
				@endif
			</td>
		</tr>
		<tr><td>{{ _lang('Transaction Type') }}</td><td>{{ ucwords(str_replace('_',' ',$transaction->type)) }}</td></tr>
		<tr><td>{{ _lang('Amount') }}</td><td>{{ $currency1.' '.decimalPlace($transaction->amount) }}</td></tr>
		<tr><td>{{ _lang('Note') }}</td><td>{{ $transaction->note }}</td></tr>
		<tr>
			<td>{{ _lang('Status') }}</td>
			<td class="status">
			   @if($transaction->status == 'pending')
					<span class="badge badge-warning">{{ _lang('Pending') }}</span>
				@elseif($transaction->status == 'complete')
					<span class="badge badge-success">{{ _lang('Completed') }}</span>
				@elseif($transaction->status == 'reject')
					<span class="badge badge-danger">{{ _lang('Rejected') }}</span>
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

@if($transaction->type == 'wire_transfer')
    </div>

	<div class="col-md-7">
		<table class="table table-striped data-table">
			<tr><td colspan="2"><b>{{ _lang('Wire Transfer Details') }}</b></td></tr>
			<tr><td>{{ _lang('Swift') }}</td><td>{{ $transaction->wire_transfer->swift }}</td></tr>
			<tr><td>{{ _lang('Bank Name') }}</td><td>{{ $transaction->wire_transfer->bank_name }}</td></tr>
			<tr><td>{{ _lang('Bank Address') }}</td><td>{{ $transaction->wire_transfer->bank_address }}</td></tr>
			<tr><td>{{ _lang('Bank Country') }}</td><td>{{ $transaction->wire_transfer->bank_country }}</td></tr>
			<tr><td>{{ _lang('Rtn') }}</td><td>{{ $transaction->wire_transfer->rtn }}</td></tr>
			<tr><td><b>{{ _lang('Customer Name') }}</b></td><td><b>{{ $transaction->wire_transfer->customer_name }}</b></td></tr>
			<tr><td>{{ _lang('Customer Address') }}</td><td>{{ $transaction->wire_transfer->customer_address }}</td></tr>
			<tr><td>{{ _lang('Customer IBAN') }}</td><td>{{ $transaction->wire_transfer->customer_iban }}</td></tr>
			<tr><td>{{ _lang('Reference Message') }}</td><td>{{ $transaction->wire_transfer->reference_message }}</td></tr>
			<tr><td>{{ _lang('Currency') }}</td><td>{{ $transaction->wire_transfer->currency }}</td></tr>
			<tr><td>{{ _lang('Amount') }}</td><td>{{ $transaction->wire_transfer->amount }}</td></tr>
		</table>	
	</div>
</div><!--End Row-->
@endif


@if($transaction->type == 'transfer')
</div>
  
     @php $currency2 = $transaction->credit->account->account_type->currency->name; @endphp
	<div class="col-md-6">
		<table class="table table-striped">
			<tr><td colspan="2"><b>{{ _lang('Credit Account Details') }}</b></td></tr>
			<tr><td>{{ _lang('Created') }}</td><td>{{ $transaction->credit->created_at }}</td></tr>
			<tr><td>{{ _lang('Account') }}</td><td>{{ $transaction->credit->account->account_number.' ('.$currency2.')' }}</td></tr>
			<tr>
				<td>{{ _lang('Debit/Credit') }}</td>
				<td>
				    @if($transaction->credit->dr_cr == 'cr')
						<span class="badge badge-success">{{ _lang('Credit') }}</span>
					@elseif($transaction->credit->dr_cr == 'dr')
						<span class="badge badge-danger">{{ _lang('Debit') }}</span>
					@endif
				</td>
			</tr>
			<tr><td>{{ _lang('Transaction Type') }}</td><td>{{ ucwords(str_replace('_',' ',$transaction->credit->type)) }}</td></tr>
			<tr><td>{{ _lang('Amount') }}</td><td>{{ $currency2.' '.decimalPlace($transaction->credit->amount) }}</td></tr>
			<tr><td>{{ _lang('Note') }}</td><td>{{ $transaction->credit->note }}</td></tr>
			<tr>
				<td>{{ _lang('Status') }}</td>
				<td class="status">
				   @if($transaction->credit->status == 'pending')
						<span class="badge badge-warning">{{ _lang('Pending') }}</span>
					@elseif($transaction->credit->status == 'complete')
						<span class="badge badge-success">{{ _lang('Completed') }}</span>
					@elseif($transaction->credit->status == 'reject')
						<span class="badge badge-danger">{{ _lang('Rejected') }}</span>
					@endif
				</td>
			</tr>
			<tr><td>{{ _lang('User') }}</td><td>{{ $transaction->credit->user->first_name.' '.$transaction->credit->user->last_name }}</td></tr>
			<tr><td>{{ _lang('User Email') }}</td><td>{{ $transaction->credit->user->email }}</td></tr>
			@if(Auth::user()->user_type == 'admin')
				<tr><td>{{ _lang('Created By') }}</td><td>{{ $transaction->credit->created_user->first_name.' ('.$transaction->credit->created_at.')' }}</td></tr>
				<tr><td>{{ _lang('Updated By') }}</td><td>{{ $transaction->credit->updated_user->first_name.' ('.$transaction->credit->updated_at.')' }}</td></tr>
			@endif
		</table>
	</div>
</div><!--End Row-->


@endif


@if($transaction->type == 'card_transfer')
    </div>

	<div class="col-md-6">
		<table class="table table-striped data-table">
			<tr><td colspan="2"><b>{{ _lang('Card Transfer Details') }}</b></td></tr>
			<tr><td>{{ _lang('Card Number') }}</td><td>{{ $transaction->card_transfer->card->card_number }}</td></tr>
			<tr><td>{{ _lang('Card Type') }}</td><td>{{ $transaction->card_transfer->card->card_type->card_type }}</td></tr>
			<tr><td>{{ _lang('Card Currency') }}</td><td>{{ card_currency($transaction->card_transfer->card_id) }}</td></tr>
			<tr><td>{{ _lang('Amount') }}</td><td>{{ $transaction->card_transfer->amount }}</td></tr>
		</table>	
	</div>
</div><!--End Row-->
@endif
