@extends('layouts.app')

@section('content')

<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="clipboard"></i></div>
				<span>{{ _lang('Payment Gateway') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="accordion" id="deposit_methods">
					  <div class="card">
						<div class="card-header params-panel" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
						  <h5 class="mb-0"><i class="mdi mdi-arrow-right-bold-circle"></i> {{ _lang('PayPal') }}</h5>
						</div>

						<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#deposit_methods">
						  <div class="card-body">
							  <form method="post" class="appsvan-submit" autocomplete="off" action="{{ url('admin/administration/general_settings/update') }}" enctype="multipart/form-data">
								{{ csrf_field() }}
								<div class="row">
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ _lang('PayPal Active') }}</label>						
										<select class="form-control" name="paypal_active" id="paypal_active" required>
										   <option value="No">{{ _lang('No') }}</option>
										   <option value="Yes">{{ _lang('Yes') }}</option>
										</select>
									  </div>
									</div>

									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ _lang('PayPal Mode') }}</label>						
										<select class="form-control" name="paypal_mode" id="paypal_mode" required>
										   <option value="sandbox">{{ _lang('Sandbox') }}</option>
										   <option value="production">{{ _lang('Production') }}</option>
										</select>
									  </div>
									</div>

									
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ _lang('PayPal CLIENT ID') }}</label>					
										<input type="text" class="form-control" name="paypal_client_id" value="{{ get_option('paypal_client_id') }}">
									  </div>
									</div>
									
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ _lang('Paypal Secret') }}</label>						
										<input type="text" class="form-control" name="paypal_secret" value="{{ get_option('paypal_secret') }}">
									  </div>
									</div>
									
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ _lang('Deposit Charge') }}</label>						
										<div class="input-group mb-3">
										  <input type="text" class="form-control" name="paypal_deposit_charge" value="{{ get_option('paypal_deposit_charge',0) }}">
										  <div class="input-group-append">
											<span class="input-group-text" id="basic-addon1">%</span>
										  </div>
										</div>
									  </div>
									</div>
									
																
									<div class="col-md-12">
									  <div class="form-group">
										<button type="submit" class="btn btn-primary pull-right">{{ _lang('Save Settings') }}</button>
									  </div>
									</div>
								</div>
							</form>
						  </div>
						</div>
					  </div>
					  <br>
					  
					  <div class="card">
						<div class="card-header params-panel" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
						  <h5 class="mb-0"><i class="mdi mdi-arrow-right-bold-circle"></i> {{ _lang('Stripe') }}</h5>
						</div>
						<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#deposit_methods">
						  <div class="card-body">
							  <form method="post" class="appsvan-submit" autocomplete="off" action="{{ url('admin/administration/general_settings/update') }}" enctype="multipart/form-data">
								{{ csrf_field() }}
									<div class="row">
										<div class="col-md-6">
										  <div class="form-group">
											<label class="control-label">{{ _lang('Stripe Active') }}</label>						
											<select class="form-control" name="stripe_active" id="stripe_active" required>
											   <option value="No">{{ _lang('No') }}</option>
											   <option value="Yes">{{ _lang('Yes') }}</option>
											</select>
										  </div>
										</div>
										
										<div class="col-md-6">
										  <div class="form-group">
											<label class="control-label">{{ _lang('Secret Key') }}</label>						
											<input type="text" class="form-control" name="stripe_secret_key" value="{{ get_option('stripe_secret_key') }}">
										  </div>
										</div>
										
										<div class="col-md-6">
										  <div class="form-group">
											<label class="control-label">{{ _lang('Publishable Key') }}</label>						
											<input type="text" class="form-control" name="stripe_publishable_key" value="{{ get_option('stripe_publishable_key') }}">
										  </div>
										</div>
										
										<div class="col-md-6">
										  <div class="form-group">
											<label class="control-label">{{ _lang('Deposit Charge') }}</label>						
											<div class="input-group mb-3">
											  <input type="text" class="form-control" name="stripe_deposit_charge" value="{{ get_option('stripe_deposit_charge',0) }}">
											  <div class="input-group-append">
												<span class="input-group-text" id="basic-addon1">%</span>
											  </div>
											</div>
										  </div>
										</div>
										
									
										<div class="col-md-12">
										  <div class="form-group">
											<button type="submit" class="btn btn-primary pull-right">{{ _lang('Save Settings') }}</button>
										  </div>
										</div>
									</div>	
								</form>
							</div>
						</div>
					  </div><!--End Stripe-->
					  <br>
					  
					  <div class="card">
						<div class="card-header params-panel" data-toggle="collapse" data-target="#blockchain" aria-expanded="true" aria-controls="blockchain">
						  <h5 class="mb-0"><i class="mdi mdi-arrow-right-bold-circle"></i> {{ _lang('Blockchain') }}</h5>
						</div>

						<div id="blockchain" class="collapse" aria-labelledby="blockchain" data-parent="#deposit_methods">
						  <div class="card-body">
							  <form method="post" class="appsvan-submit" autocomplete="off" action="{{ url('admin/administration/general_settings/update') }}" enctype="multipart/form-data">
								{{ csrf_field() }}
								<div class="row">
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ _lang('BlockChain Active') }}</label>						
										<select class="form-control" name="blockchain_active" id="blockchain_active" required>
										   <option value="No">{{ _lang('No') }}</option>
										   <option value="Yes">{{ _lang('Yes') }}</option>
										</select>
									  </div>
									</div>
									
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ _lang('Blockchain API key') }}</label>					
										<input type="text" class="form-control" name="blockchain_api_key" value="{{ get_option('blockchain_api_key') }}">
									  </div>
									</div>
									
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ _lang('Blockchain Xpub') }}</label>						
										<input type="text" class="form-control" name="blockchain_xpub" value="{{ get_option('blockchain_xpub') }}">
									  </div>
									</div>
									
									<div class="col-md-6">
									  <div class="form-group">
										<label class="control-label">{{ _lang('Deposit Charge') }}</label>						
										<div class="input-group mb-3">
										  <input type="text" class="form-control" name="blockchain_deposit_charge" value="{{ get_option('blockchain_deposit_charge',0) }}">
										  <div class="input-group-append">
											<span class="input-group-text" id="basic-addon1">%</span>
										  </div>
										</div>
									  </div>
									</div>
																
									<div class="col-md-12">
									  <div class="form-group">
										<button type="submit" class="btn btn-primary pull-right">{{ _lang('Save Settings') }}</button>
									  </div>
									</div>
								</div>
							</form>
						  </div>
						</div>
					  </div>
					  <br>
					  
					  <div class="card">
						<div class="card-header params-panel" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
						  <h5 class="mb-0"><i class="mdi mdi-arrow-right-bold-circle"></i> {{ _lang('Bank Wire Transfer') }}</h5>
						</div>
						<div id="collapseThree" class="collapse" data-parent="#deposit_methods">
						  <div class="card-body">
							  <form method="post" class="appsvan-submit" autocomplete="off" action="{{ url('admin/administration/general_settings/update') }}" enctype="multipart/form-data">
								{{ csrf_field() }}
									<div class="row">
										<div class="col-md-6">
										  <div class="form-group">
											<label class="control-label">{{ _lang('Wire Transfer Active') }}</label>						
											<select class="form-control" name="wire_transfer_active" id="wire_transfer_active" required>
											   <option value="No">{{ _lang('No') }}</option>
											   <option value="Yes">{{ _lang('Yes') }}</option>
											</select>
										  </div>
										</div>
										
										<div class="col-md-6">
										  <div class="form-group">
											<label class="control-label">{{ _lang('Bank Name') }}</label>						
											<input type="text" class="form-control" name="wire_transfer_bank_name" value="{{ get_option('wire_transfer_bank_name') }}">
										  </div>
										</div>
										
										<div class="col-md-6">
										  <div class="form-group">
											<label class="control-label">{{ _lang('Account Name') }}</label>						
											<input type="text" class="form-control" name="wire_transfer_account_name" value="{{ get_option('wire_transfer_account_name') }}">
										  </div>
										</div>
										
										<div class="col-md-6">
										  <div class="form-group">
											<label class="control-label">{{ _lang('Account Number') }}</label>						
											<input type="text" class="form-control" name="wire_transfer_account_number" value="{{ get_option('wire_transfer_account_number') }}">
										  </div>
										</div>
										
										<div class="col-md-6">
										  <div class="form-group">
											<label class="control-label">{{ _lang('Bank Routing Number') }}</label>						
											<input type="text" class="form-control" name="wire_transfer_routing_number" value="{{ get_option('wire_transfer_routing_number') }}">
										  </div>
										</div>
										
										<div class="col-md-6">
										  <div class="form-group">
											<label class="control-label">{{ _lang('IBAN Number') }}</label>						
											<input type="text" class="form-control" name="wire_transfer_iban_number" value="{{ get_option('wire_transfer_iban_number') }}">
										  </div>
										</div>
										
										<div class="col-md-6">
										  <div class="form-group">
											<label class="control-label">{{ _lang('SWIFT/BIC Code') }}</label>						
											<input type="text" class="form-control" name="wire_transfer_swift_code" value="{{ get_option('wire_transfer_swift_code') }}">
										  </div>
										</div>
										
										<div class="col-md-6">
										  <div class="form-group">
											<label class="control-label">{{ _lang('Deposit Charge') }}</label>						
											<div class="input-group mb-3">
											  <input type="text" class="form-control" name="wire_deposit_charge" value="{{ get_option('wire_deposit_charge',0) }}">
											   <div class="input-group-append">
												  <span class="input-group-text" id="basic-addon1">%</span>
											   </div>
											</div>
										  </div>
										</div>
										
																			
										<div class="col-md-12">
										  <div class="form-group">
											<button type="submit" class="btn btn-primary pull-right">{{ _lang('Save Settings') }}</button>
										  </div>
										</div>
									</div>	
								</form>
							</div>
						</div>
					  </div><!--End Bank Transfer-->
					  
					  
					</div>
				</div>
			</div>  
		</div>
	</div>
</div>
@endsection

@section('js-script')
<script type="text/javascript">
$("#paypal_active").val("{{ get_option('paypal_active','No') }}");
$("#paypal_mode").val("{{ get_option('paypal_mode','sandbox') }}");
$("#stripe_active").val("{{ get_option('stripe_active','No') }}");
$("#blockchain_active").val("{{ get_option('blockchain_active','No') }}");
$("#wire_transfer_active").val("{{ get_option('wire_transfer_active','No') }}");
</script>
@stop

