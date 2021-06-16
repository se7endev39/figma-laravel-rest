@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="arrow-right-circle"></i></div>
				<span>{{ _lang('Wire Transfer Deposit') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="{{ $method == '' ? 'col-md-6' : 'col-md-8' }}">
			<div class="card">
				<div class="card-body">
					@if($method == '')
						<h4 class="card-title panel-title">{{ _lang('Load Money Using Wire Transfer') }}</h4>
						<form method="post" class="validate" autocomplete="off" action="{{ url('user/load_money/wire_transfer') }}">
							{{ csrf_field() }}
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Credit Account') }}</label>						
										<select class="form-control select2" name="credit_account" required="true" {{ $method == 'PayPal' ? 'disabled' : '' }}>
											@foreach(\App\Account::where('user_id',Auth::id())->where('status',1)->get() as $account )
												<option value="{{ $account->id }}" {{ $credit_account == $account->id ? 'selected' : '' }}>{{ $account->account_number.' - '.$account->account_type->account_type.' ('.$account->account_type->currency->name.')' }}</option>
											@endforeach
										</select>
									</div>
								</div>
								
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Amount') }}</label>								
										<input type="text" class="form-control float-field" name="amount" value="{{ old('amount',$amount) }}" required="true" {{ $method == 'PayPal' ? 'disabled' : '' }}>
									</div>
								</div>
							
								<div class="col-md-12">
									<div class="form-group">
										<button type="submit" class="btn btn-primary">{{ _lang('Process') }}</button>
									</div>
								</div>
							</div>			
						</form>
					@else
						<div class="alert alert-info">
							<h6>{{ _lang('Your balance will be appear in your account') }} <b>{{ $deposit_request->account->account_number }}</b> {{ _lang('after your transaction get approved by our team') }}.</h6>
							<table class="table table-striped mt-4">
							  <tr>
								 <td colspan="2"><h5>{{ _lang('Bank Details for Payment') }}</h5></td>
							  </tr>
							  <tr>
								 <td><b>{{ _lang('Bank Name') }}</b></td>
								 <td>{{ get_option('wire_transfer_bank_name') }}</td>
							  </tr>
							  <tr>
								 <td><b>{{ _lang('Account Holder Name') }}</b></td>
								 <td>{{ get_option('wire_transfer_account_name') }}</td>
							  </tr>
							  <tr>
								 <td><b>{{ _lang('Account Number') }}</b></td>
								 <td>{{ get_option('wire_transfer_account_number') }}</td>
							  </tr>
							  <tr>
								 <td><b>{{ _lang('Routing Number') }}</b></td>
								 <td>{{ get_option('wire_transfer_routing_number') }}</td>
							  </tr>
							  <tr>
								 <td><b>{{ _lang('Swift/BIC') }}</b></td>
								 <td>{{ get_option('wire_transfer_swift_code') }}</td>
							  </tr>
							  <tr>
								 <td><b>{{ _lang('IBAN Number') }}</b></td>
								 <td>{{ get_option('wire_transfer_iban_number') }}</td>
							  </tr>
							  <tr>
								 <td><b>{{ _lang('Amount') }}</b></td>
								 <td>{{ account_currency($deposit_request->credit_account).' '.decimalPlace($deposit_request->amount) }}</td>
							  </tr>
							  
							  <tr>
								 <td><b>{{ _lang('Charge') }}</b></td>
								 <td>{{ account_currency($deposit_request->credit_account).' '.decimalPlace($deposit_request->charge) }}</td>
							  </tr>
							  
							  <tr>
								 <td><b>{{ _lang('Total Deposit Amount') }}</b></td>
								 <td>{{ account_currency($deposit_request->credit_account).' '.decimalPlace($deposit_request->amount + $deposit_request->charge) }}</td>
							  </tr>
							
							</table>
							
							<p class="text-danger"><strong>{{ _lang('Use this Transaction ID') }} <b>(#{{ $deposit_request->transaction_id }})</b> {{ _lang('as reference. make this payment within 24 hours. if we will not get this payment within 24 hours, then we may cancel this transaction') }}.</strong></p>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

