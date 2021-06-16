@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="arrow-right-circle"></i></div>
				<span>{{ _lang('Stripe Deposit') }}</span>
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
					<h4 class="card-title panel-title">{{ _lang('Load Money Using Stripe') }}</h4>
					@if($method == '')
						<form method="post" class="validate" autocomplete="off" action="{{ url('user/load_money/stripe') }}">
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
										<button type="submit" class="btn btn-primary">{{ _lang('Process Via Stripe') }}</button>
									</div>
								</div>
							</div>			
						</form>
					@elseif($method == 'Stripe')
						<form method="POST" id="paymentFrm" action="{{ url('user/load_money/stripe_authorization') }}">
							@csrf
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
										<input type="text" class="form-control float-field" name="amount" value="{{ account_currency($credit_account).' '.decimalPlace($amount) }}" disabled>
									</div>
								</div>
								
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Payable Amount').' + '._lang('Transaction Charge') }}</label>								
										<input type="text" class="form-control float-field" value="USD {{ decimalPlace($converted_amount) }}" disabled>
									</div>
								</div>
								
								<input type="hidden" name="payable_amount" value="{{ decimalPlace($converted_amount) }}">
								<input type="hidden" name="credit_account" value="{{ $credit_account }}">
								
								<div class="col-md-12">
									<div class="alert alert-danger payment-status d-none">
										
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="name">{{ _lang('CARDHOLDER NAME') }}</label>
										<input type="text" class="form-control" name="name" placeholder="{{ _lang('Cardholder Name') }}" required>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="cardNumber">{{ _lang('CARD NUMBER') }}</label>
										<input type="text" class="form-control cc" id="card_number" name="cardNumber" placeholder="{{ _lang('Card Number') }}" required>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="cardExpiry">{{ _lang('EXPIRY DATE') }}</label>
										<div class="row">
										    <div class="col-md-5">
												<input type="text" class="form-control" id="card_exp_month" placeholder="MM" name="card_exp_month" maxlength="2" required>
											</div>
											<div class="col-md-7">
												<input type="text" class="form-control" id="card_exp_year" placeholder="YYYY" name="card_exp_year" maxlength="4" required>
									        </div>
										</div>
									</div>
								</div>
								
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="cardCVC">{{ _lang('CVC CODE') }}</label>
										<input type="text" class="form-control cvv" id="card_cvc" name="cardCVC" placeholder="CVC" required>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<button type="submit" class="btn btn-primary">{{ _lang('PAY NOW') }}</button>
									</div>
								</div>
							</div>
						</form>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js-script')
<script src="https://js.stripe.com/v2/"></script>

<script>
// Set your publishable key
Stripe.setPublishableKey("{{ get_option('stripe_publishable_key') }}");

// Callback to handle the response from stripe
function stripeResponseHandler(status, response) {
	console.log(response);
    if (response.error) {
        // Enable the submit button
        $('#payBtn').removeAttr("disabled");
        // Display the errors on the form
        $(".payment-status").html('<p>'+response.error.message+'</p>');
        $(".payment-status").removeClass('d-none');
    } else {
		$(".payment-status").addClass('d-none');
        var form$ = $("#paymentFrm");
        // Get token id
        var token = response.id;
        // Insert the token into the form
        form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
        // Submit form to the server
        form$.get(0).submit();
    }
}

$(document).ready(function() {
    // On form submit
    $("#paymentFrm").submit(function() {
        // Disable the submit button to prevent repeated clicks
        $('#payBtn').attr("disabled", "disabled");
		
        // Create single-use token to charge the user
        Stripe.createToken({
            number: $('#card_number').val(),
            exp_month: $('#card_exp_month').val(),
            exp_year: $('#card_exp_year').val(),
            cvc: $('#card_cvc').val()
        }, stripeResponseHandler);
		
        // Submit from callback
        return false;
    });
});
</script>
@endsection