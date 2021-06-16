@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="arrow-right-circle"></i></div>
				<span>{{ _lang('PayPal Deposit') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-md-6">

			@if(Session::has('success'))
				<div class="alert alert-success">
				   <button type="button" class="close" data-dismiss="alert">&times;</button>
	               <strong>{{ session('success') }}</strong>
				</div>	
			@endif

			<div class="card">
				<div class="card-body">
					<h4 class="card-title panel-title">{{ _lang('Load Money Using PayPal') }}</h4>
					<form method="post" class="validate" autocomplete="off" action="{{ url('user/load_money/paypal') }}">
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

							@if($method == '')
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Amount') }}</label>								
										<input type="text" class="form-control float-field" name="amount" value="{{ old('amount',$amount) }}" required="true" {{ $method == 'PayPal' ? 'disabled' : '' }}>
									</div>
								</div>
							
								<div class="col-md-12">
									<div class="form-group">
										<button type="submit" class="btn btn-primary">{{ _lang('Process Via PayPal') }}</button>
									</div>
								</div>
							@elseif($method == 'PayPal')
							    <div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Amount') }}</label>								
										<input type="text" class="form-control float-field" name="amount" value="{{ account_currency($credit_account).' '.decimalPlace($amount) }}" disabled>
									</div>
								</div>
								
							    <div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Payable Amount').' + '._lang('Transaction Charge') }}</label>								
										<input type="text" class="form-control float-field" value="USD {{ decimalPlace($converted_amount) }}" disabled>
									</div>
								</div>
							
							    <div class="col-md-12">
									<div class="form-group">
										
										<!--PayPal Pay Now Button-->
										<script src="https://www.paypal.com/sdk/js?client-id={{ get_option('paypal_client_id') }}&currency=USD&disable-funding=credit,card"></script>
										<div id="paypal-button-container"></div>
										
										<script>
										  paypal.Buttons({
											createOrder: function(data, actions) {
											  // This function sets up the details of the transaction, including the amount and line item details.
											  return actions.order.create({
												purchase_units: [{
												  amount: {
													value: '{{ decimalPlace($converted_amount) }}'
												  }
												}]
											  });
											},
											onApprove: function(data, actions) {
											  
												window.location.href = "{{ url('user/load_money/paypal_payment_authorize') }}/" + data.orderID + "/" + {{ $credit_account }};

											  /*return actions.order.capture().then(function(details) {
												//var status = details.status; //COMPLETED
												//var payer = details.payer.email_address;
												//var gross_amount = details.purchase_units[0].amount.value;
												//var currency = details.purchase_units[0].amount.currency_code;
											  });*/
											},
											onCancel: function (data) {
												swal({
												  title: "{{ _lang('Alert') }}",
												  text: "{{ _lang('Payment Canceled') }}",
												  icon: "warning",
												});
											}
										  }).render('#paypal-button-container');
										  
										</script>
									@endif
								</div>			
							</div>			
						</div>			
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

