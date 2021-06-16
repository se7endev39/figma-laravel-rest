@extends('layouts.app')

@section('content')

<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="settings"></i></div>
				<span>{{ _lang('General Settings') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<ul class="nav nav-tabs">
						<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#general">{{ _lang('General Settings') }}</a></li>
						<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#banking">{{ _lang('Banking Settings') }}</a></li>
						<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#custom_signup">{{ _lang('Custom Signup') }}</a></li>
						<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#email">{{ _lang('Email Settings') }}</a></li>
						<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#sms">{{ _lang('SMS Settings') }}</a></li>
						<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#payment_gateway">{{ _lang('Payment Gateway') }}</a></li>
						<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#logo">{{ _lang('Logo') }}</a></li>
					</ul>
					<div class="tab-content">
						
						<div id="general" class="tab-pane active">
							<div class="card">
								<div class="card-body">
									<h4 class="card-title panel-title">{{ _lang('General Settings') }}</h4>
									<form method="post" class="appsvan-submit params-panel" autocomplete="off" action="{{ url('admin/administration/general_settings/update') }}" enctype="multipart/form-data">
										{{ csrf_field() }}
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Company Name') }}</label>						
													<input type="text" class="form-control" name="company_name" value="{{ get_option('company_name') }}" required>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Site Title') }}</label>						
													<input type="text" class="form-control" name="site_title" value="{{ get_option('site_title') }}" required>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Phone') }}</label>						
													<input type="text" class="form-control" name="phone" value="{{ get_option('phone') }}">
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Email') }}</label>						
													<input type="text" class="form-control" name="email" value="{{ get_option('email') }}" required>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Timezone') }}</label>						
													<select class="form-control select2" name="timezone" required>
														<option value="">{{ _lang('-- Select One --') }}</option>
														{{ create_timezone_option(get_option('timezone')) }}
													</select>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Language') }}</label>						
													<select class="form-control select2" name="language" required>
														{!! load_language( get_option('language') ) !!}
													</select>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Email Verification') }}</label>						
													<select class="form-control" name="email_verification" required>
														<option value="No" {{ get_option('email_verification') == 'No' ? 'selected' : '' }}>{{ _lang('No') }}</option>
														<option value="Yes" {{ get_option('email_verification') == 'Yes' ? 'selected' : '' }}>{{ _lang('Yes') }}</option>
													</select>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Copyright Text') }}</label>						
													<input type="text" class="form-control" name="copyright" value="{{ get_option('copyright') }}">
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Address') }}</label>						
													<textarea class="form-control" name="address">{{ get_option('address') }}</textarea>
												</div>
											</div>

											
											<div class="col-md-12">
												<div class="form-group">
													<button type="submit" class="btn btn-primary">{{ _lang('Save Settings') }}</button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>

						<div id="banking" class="tab-pane">
							<div class="card">
								<div class="card-body">
									<h4 class="card-title panel-title">{{ _lang('Banking Settings') }}</h4>
									<form method="post" class="appsvan-submit params-panel" autocomplete="off" action="{{ url('admin/administration/general_settings/update') }}" enctype="multipart/form-data">
										{{ csrf_field() }}
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Account Number Prefix').' ('._lang('Max 10').')' }}</label>						
													<input type="text" class="form-control" name="account_number_prefix" maxlength="10" value="{{ get_option('account_number_prefix') }}" required>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Next Account Number') }}</label>						
													<input type="number" class="form-control" name="next_account_number" maxlength="10" value="{{ get_option('next_account_number',date('Y').'1001') }}" required>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Currency Converter') }}</label>		
													<select class="form-control" name="currency_converter" required>
                                                        <option value="manual" {{ get_option('currency_converter') == 'manual' ? 'selected' : '' }}>{{ _lang('Manual') }}</option>
                                                        <option value="fixer" {{ get_option('currency_converter') == 'fixer' ? 'selected' : '' }}>{{ _lang('Fixer API') }}</option>
													</select>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Fixer API Key') }}</label>
													<a href="https://fixer.io/" target="_blank" class="float-right">{{ _lang('GET API KEY') }}</a>		
													<input type="text" class="form-control" name="fixer_api_key" value="{{ get_option('fixer_api_key') }}">
												</div>
											</div>
											
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Currency Exchange Fee') }} (%)</label>	
													<input type="text" class="form-control float-field" name="currency_exchange_fee" value="{{ get_option('currency_exchange_fee',0) }}">
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Allow Singup') }}</label>						
													<select class="form-control" name="allow_singup" required>
														<option value="yes" {{ get_option('allow_singup') == 'yes' ? 'selected' : '' }}>{{ _lang('Yes') }}</option>
														<option value="no" {{ get_option('allow_singup') == 'no' ? 'selected' : '' }}>{{ _lang('No') }}</option>
													</select>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Need Approval On Transfer Between Users') }}</label>						
													<select class="form-control" name="tbu_approval" required>
														<option value="no" {{ get_option('tbu_approval') == 'no' ? 'selected' : '' }}>{{ _lang('No') }}</option>
														<option value="yes" {{ get_option('tbu_approval') == 'yes' ? 'selected' : '' }}>{{ _lang('Yes') }}</option>
													</select>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Need Approval On Transfer Between Accounts') }}</label>						
													<select class="form-control" name="tba_approval" required>
														<option value="no" {{ get_option('tba_approval') == 'no' ? 'selected' : '' }}>{{ _lang('No') }}</option>
														<option value="yes" {{ get_option('tba_approval') == 'yes' ? 'selected' : '' }}>{{ _lang('Yes') }}</option>
													</select>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('User Referral commission') }} (%)</label>	
													<input type="text" class="form-control float-field" name="user_referral_commission" value="{{ get_option('user_referral_commission') }}">
												</div>
											</div>

											
											<div class="col-md-12">
												<div class="form-group">
													<button type="submit" class="btn btn-primary">{{ _lang('Save Settings') }}</button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>

						
						<div id="custom_signup" class="tab-pane">
							<div class="card">
								<div class="card-body">
									<h4 class="card-title"><span class="panel-title">{{ _lang('Custom Signup') }}</span>
										<button class="btn btn-primary btn-sm float-right ajax-modal" data-title="{{ _lang('Add New Field') }}" data-href="{{ route('custom_fields.create') }}">{{ _lang('Add New Field') }}</button>
										<button class="btn btn-secondary btn-sm float-right ajax-modal mr-1" data-title="{{ _lang('Add New Section') }}" data-href="{{ route('custom_field_sections.create') }}">{{ _lang('Add New Section') }}</button>
										<a class="btn btn-dark btn-sm float-right mr-1" href="{{route('custom_field_sections.index')}}">{{ _lang('Section List') }}</a>
									</h4>
									<div class="table-responsive">
										<table id="custom_fields_table" class="table table-bordered">
											<thead>
												<tr>
													<th>{{ _lang('Field Name') }}</th>
													<th>{{ _lang('Field Type') }}</th>
													<th>{{ _lang('Required') }}</th>
													<th>{{ _lang('Status') }}</th>
													<th class="text-center">{{ _lang('Action') }}</th>
												</tr>
											</thead>
											<tbody>
												@foreach($customfields as $customfield)
												<tr data-id="row_{{ $customfield->id }}">
													<td class='field_name'>{{ $customfield->field_name }}</td>
													<td class='field_type'>{{ ucwords($customfield->field_type) }}</td>
													<td class='validation_rules'>{{ ucwords($customfield->validation_rules) }}</td>
													<td class='status'>
														{{ $customfield->status == 1 ? _lang('Active') : _lang('In Active') }}
													</td>
													<td class="text-center">
														<div class="dropdown">
														  <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														  {{ _lang('Action') }}
														  </button>
														  <form action="{{ action('CustomFieldController@destroy', $customfield['id']) }}" method="post">
															{{ csrf_field() }}
															<input name="_method" type="hidden" value="DELETE">
															
															<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
																<button data-href="{{ action('CustomFieldController@edit', $customfield['id']) }}" data-title="{{ _lang('Update Custom Field') }}" class="dropdown-item ajax-modal"><i class="mdi mdi-pencil"></i> {{ _lang('Edit') }}</button>
																<button data-href="{{ action('CustomFieldController@show', $customfield['id']) }}" data-title="{{ _lang('View Custom Field') }}" class="dropdown-item ajax-modal"><i class="mdi mdi-eye"></i> {{ _lang('View') }}</button>
																<button class="btn-remove dropdown-item" type="submit"><i class="mdi mdi-delete"></i> {{ _lang('Delete') }}</button>
															</div>
														  </form>
														</div>
													</td>
												</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>


						<div id="email" class="tab-pane fade">
							<div class="card"> 
								<div class="card-body">
									<h4 class="card-title panel-title">{{ _lang('Email Settings') }}</h4>
									<form method="post" class="appsvan-submit params-panel" autocomplete="off" action="{{ url('admin/administration/general_settings/update') }}" enctype="multipart/form-data">
										{{ csrf_field() }}
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Mail Type') }}</label>						
													<select class="form-control niceselect wide" name="mail_type" id="mail_type" required>
														<option value="mail" {{ get_option('mail_type')=="mail" ? "selected" : "" }}>{{ _lang('PHP Mail') }}</option>
														<option value="smtp" {{ get_option('mail_type')=="smtp" ? "selected" : "" }}>{{ _lang('SMTP') }}</option>
													</select>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('From Email') }}</label>						
													<input type="text" class="form-control" name="from_email" value="{{ get_option('from_email') }}" required>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('From Name') }}</label>						
													<input type="text" class="form-control" name="from_name" value="{{ get_option('from_name') }}" required>
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('SMTP Host') }}</label>						
													<input type="text" class="form-control smtp" name="smtp_host" value="{{ get_option('smtp_host') }}">
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('SMTP Port') }}</label>						
													<input type="text" class="form-control smtp" name="smtp_port" value="{{ get_option('smtp_port') }}">
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('SMTP Username') }}</label>						
													<input type="text" class="form-control smtp" autocomplete="off" name="smtp_username" value="{{ get_option('smtp_username') }}">
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('SMTP Password') }}</label>						
													<input type="password" class="form-control smtp" autocomplete="off" name="smtp_password" value="{{ get_option('smtp_password') }}">
												</div>
											</div>

											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('SMTP Encryption') }}</label>						
													<select class="form-control smtp" name="smtp_encryption">
														<option value="ssl" {{ get_option('smtp_encryption')=="ssl" ? "selected" : "" }}>{{ _lang('SSL') }}</option>
														<option value="tls" {{ get_option('smtp_encryption')=="tls" ? "selected" : "" }}>{{ _lang('TLS') }}</option>
													</select>
												</div>
											</div>

											<div class="col-md-12">
												<div class="form-group">
													<button type="submit" class="btn btn-primary">{{ _lang('Save Settings') }}</button>
												</div>
											</div>
										</div>	
									</form>
								</div>
							</div>
						</div>
						
						<div id="sms" class="tab-pane fade">
							<div class="card"> 
								<div class="card-body">
									<h4 class="card-title panel-title">{{ _lang('SMS Settings') }}</h4>
									<form method="post" class="appsvan-submit params-panel" autocomplete="off" action="{{ url('admin/administration/general_settings/update') }}">
										{{ csrf_field() }}
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Allow SMS Notification') }}</label>						
													<select class="form-control" name="sms_notification" id="sms_notification" required>
														<option value="no">{{ _lang('No') }}</option>
														<option value="yes">{{ _lang('Yes') }}</option>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Twilio ACCOUNT SID') }}</label>						
													<input type="text" class="form-control" name="twilio_account_sid" value="{{ get_option('twilio_account_sid') }}" required>
												</div>
											</div>
										</div>	

										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Twilio AUTH TOKEN') }}</label>						
													<input type="text" class="form-control" name="twilio_auth_token" value="{{ get_option('twilio_auth_token') }}" required>
												</div>
											</div>
										</div>	
										
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Twilio Mobile Number') }}</label>						
													<input type="text" class="form-control" name="twilio_number" value="{{ get_option('twilio_number') }}" required>
												</div>
											</div>
										</div>	

										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<button type="submit" class="btn btn-primary">{{ _lang('Save Settings') }}</button>
												</div>
											</div>
										</div>	
									</form>
								</div>
							</div>
						</div>

						<div id="payment_gateway" class="tab-pane fade">
							<div class="card">
								<div class="card-body">
									<h4 class="card-title panel-title">{{ _lang('Payment Gateway') }}</h4>
									

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

						<div id="logo" class="tab-pane fade">
							<div class="card">
								<div class="card-body">
									<form method="post" class="appsvan-submit params-panel" autocomplete="off" action="{{ url('admin/administration/upload_logo') }}" enctype="multipart/form-data">				         
										<h4 class="card-title panel-title">{{ _lang('Logo Upload') }}</h4>
										{{ csrf_field() }}
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">{{ _lang('Upload Logo') }}</label>						
													<input type="file" class="form-control dropify" name="logo" data-max-file-size="8M" data-allowed-file-extensions="png jpg jpeg PNG JPG JPEG" data-default-file="{{ get_logo() }}" required>
												</div>
											</div>
										</div>	

										<div class="row">	
											<div class="col-md-6">
												<div class="form-group">
													<button type="submit" class="btn btn-primary btn-block">{{ _lang('Upload') }}</button>
												</div>
											</div>	
										</div>
									</form>	
								</div>
							</div>
						</div>		
					</div>  
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>
@endsection

@section('js-script')
<script>
(function($) {
    "use strict";
	
	if($("#mail_type").val() != "smtp"){
		$(".smtp").prop("disabled",true);
	}
	$(document).on("change","#mail_type",function(){
		if( $(this).val() != "smtp" ){
			$(".smtp").prop("disabled",true);
		}else{
			$(".smtp").prop("disabled",false);
		}
	});
	
	$("#paypal_active").val("{{ get_option('paypal_active','No') }}");
	$("#paypal_mode").val("{{ get_option('paypal_mode','sandbox') }}");
	$("#stripe_active").val("{{ get_option('stripe_active','No') }}");
	$("#blockchain_active").val("{{ get_option('blockchain_active','No') }}");
	$("#wire_transfer_active").val("{{ get_option('wire_transfer_active','No') }}");
	$("#sms_notification").val("{{ get_option('sms_notification','no') }}");

	$('.nav-tabs a').on('shown.bs.tab', function(event){
		var tab = $(event.target).attr("href");
		var url = "{{ url('admin/administration/general_settings') }}";
	    history.pushState({}, null, url + "?tab=" + tab.substring(1));
	});

	@if(isset($_GET['tab']))
	   $('.nav-tabs a[href="#{{ $_GET['tab'] }}"]').tab('show');
	@endif
		   
})(jQuery);
</script>
@endsection

