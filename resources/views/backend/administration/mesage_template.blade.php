@extends('layouts.app')

@section('content')

<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="clipboard"></i></div>
				<span>{{ _lang('Message Template') }}</span>
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
						<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#deposit">{{ _lang('Deposit') }}</a></li>
						<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#withdraw">{{ _lang('Withdraw') }}</a></li>
						<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#request_approved">{{ _lang('Request Approved') }}</a></li>
						<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#request_rejected">{{ _lang('Request Rejected') }}</a></li>
						<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#received_payment">{{ _lang('Received Payment') }}</a></li>
						<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#loan_approved">{{ _lang('Loan Approved') }}</a></li>
					</ul>
					<div class="tab-content">

						<div id="deposit" class="tab-pane active">
							<div class="card">
								<div class="card-body">
									<h4 class="card-title panel-title">{{ _lang('Deposit Message Template') }}</h4>
									<form method="post" class="appsvan-submit params-panel" autocomplete="off" action="{{ url('admin/administration/general_settings/update') }}" enctype="multipart/form-data">
										{{ csrf_field() }}
										<div class="row">
										    <div class="col-md-12">
												<div class="form-group">					
													<span>{first_name} {last_name} {account} {currency} {amount} {date}</span>
												</div>
											</div>
											
											<div class="col-md-12">
												<div class="form-group">					
													<label>{{ _lang('Subject') }}</label>
													<input type="text" class="form-control" name="deposit_subject" value="{{ get_option('deposit_subject') }}" required>
											    </div>
											</div>
											
											<div class="col-md-12">
												<div class="form-group">					
													<label>{{ _lang('Message') }}</label>
													<textarea class="form-control summernote" name="deposit_message" required>{{ get_option('deposit_message') }}</textarea>
												</div>
											</div>
											
											<div class="col-md-12">
												<div class="form-group">
													<button type="submit" class="btn btn-primary">{{ _lang('Save Template') }}</button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div> <!--End Deposit-->
						
						<div id="withdraw" class="tab-pane">
							<div class="card">
								<div class="card-body">
									<h4 class="card-title panel-title">{{ _lang('Withdraw Message Template') }}</h4>
									<form method="post" class="appsvan-submit params-panel" autocomplete="off" action="{{ url('admin/administration/general_settings/update') }}" enctype="multipart/form-data">
										{{ csrf_field() }}
										<div class="row">
										    <div class="col-md-12">
												<div class="form-group">					
													<span>{first_name} {last_name} {account} {currency} {amount} {date}</span>
												</div>
											</div>
											
											<div class="col-md-12">
												<div class="form-group">					
													<label>{{ _lang('Subject') }}</label>
													<input type="text" class="form-control" name="withdraw_subject" value="{{ get_option('withdraw_subject') }}" required>
											    </div>
											</div>
											
											<div class="col-md-12">
												<div class="form-group">	
                                                    <label>{{ _lang('Message') }}</label>												
													<textarea class="form-control summernote" name="withdraw_message" required>{{ get_option('withdraw_message') }}</textarea>
												</div>
											</div>
											
											<div class="col-md-12">
												<div class="form-group">
													<button type="submit" class="btn btn-primary">{{ _lang('Save Template') }}</button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div> <!--End Withdraw-->
						
						<div id="request_approved" class="tab-pane">
							<div class="card">
								<div class="card-body">
									<h4 class="card-title panel-title">{{ _lang('Request Approved Message Template') }}</h4>
									<form method="post" class="appsvan-submit params-panel" autocomplete="off" action="{{ url('admin/administration/general_settings/update') }}" enctype="multipart/form-data">
										{{ csrf_field() }}
										<div class="row">
										    <div class="col-md-12">
												<div class="form-group">					
													<span>{first_name} {last_name} {account} {currency} {amount} {date}</span>
												</div>
											</div>
											
											<div class="col-md-12">
												<div class="form-group">					
													<label>{{ _lang('Subject') }}</label>
													<input type="text" class="form-control" name="request_approved_subject" value="{{ get_option('request_approved_subject') }}" required>
											    </div>
											</div>
											
											<div class="col-md-12">
												<div class="form-group">					
													<label>{{ _lang('Message') }}</label>
													<textarea class="form-control summernote" name="request_approved_message" required>{{ get_option('request_approved_message') }}</textarea>
												</div>
											</div>
											
											<div class="col-md-12">
												<div class="form-group">
													<button type="submit" class="btn btn-primary">{{ _lang('Save Template') }}</button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div> <!--End Request Approved-->
						
						<div id="request_rejected" class="tab-pane">
							<div class="card">
								<div class="card-body">
									<h4 class="card-title panel-title">{{ _lang('Request Rejected Message Template') }}</h4>
									<form method="post" class="appsvan-submit params-panel" autocomplete="off" action="{{ url('admin/administration/general_settings/update') }}" enctype="multipart/form-data">
										{{ csrf_field() }}
										<div class="row">
										    <div class="col-md-12">
												<div class="form-group">					
													<span>{first_name} {last_name} {account} {currency} {amount} {date}</span>
												</div>
											</div>
											
											<div class="col-md-12">
												<div class="form-group">					
													<label>{{ _lang('Subject') }}</label>
													<input type="text" class="form-control" name="request_rejected_subject" value="{{ get_option('request_rejected_subject') }}" required>
											    </div>
											</div>
											
											<div class="col-md-12">
												<div class="form-group">					
													<label>{{ _lang('Message') }}</label>
													<textarea class="form-control summernote" name="request_rejected_message" required>{{ get_option('request_rejected_message') }}</textarea>
												</div>
											</div>
											
											<div class="col-md-12">
												<div class="form-group">
													<button type="submit" class="btn btn-primary">{{ _lang('Save Template') }}</button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div> <!--End Request Rejected-->
						
						<div id="received_payment" class="tab-pane">
							<div class="card">
								<div class="card-body">
									<h4 class="card-title panel-title">{{ _lang('Payment Received Message Template') }}</h4>
									<form method="post" class="appsvan-submit params-panel" autocomplete="off" action="{{ url('admin/administration/general_settings/update') }}" enctype="multipart/form-data">
										{{ csrf_field() }}
										<div class="row">
										    <div class="col-md-12">
												<div class="form-group">					
													<span>{first_name} {last_name} {account} {currency} {amount} {date} {payer}</span>
												</div>
											</div>
											
											<div class="col-md-12">
												<div class="form-group">					
													<label>{{ _lang('Subject') }}</label>
													<input type="text" class="form-control" name="payment_received_subject" value="{{ get_option('payment_received_subject') }}" required>
											    </div>
											</div>
											
											<div class="col-md-12">
												<div class="form-group">					
													<label>{{ _lang('Message') }}</label>
													<textarea class="form-control summernote" name="payment_received_message" required>{{ get_option('payment_received_message') }}</textarea>
												</div>
											</div>
											
											<div class="col-md-12">
												<div class="form-group">
													<button type="submit" class="btn btn-primary">{{ _lang('Save Template') }}</button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div> <!--End Request Rejected-->


						<div id="loan_approved" class="tab-pane">
							<div class="card">
								<div class="card-body">
									<h4 class="card-title panel-title">{{ _lang('Payment Received Message Template') }}</h4>
									<form method="post" class="appsvan-submit params-panel" autocomplete="off" action="{{ url('admin/administration/general_settings/update') }}" enctype="multipart/form-data">
										{{ csrf_field() }}
										<div class="row">
										    <div class="col-md-12">
												<div class="form-group">					
													<span>{first_name} {last_name} {loan_id} {currency} {account} {applied_amount}</span>
												</div>
											</div>
											
											<div class="col-md-12">
												<div class="form-group">					
													<label>{{ _lang('Subject') }}</label>
													<input type="text" class="form-control" name="loan_approved_subject" value="{{ get_option('loan_approved_subject') }}" required>
											    </div>
											</div>
											
											<div class="col-md-12">
												<div class="form-group">					
													<label>{{ _lang('Message') }}</label>
													<textarea class="form-control summernote" name="loan_approved_message" required>{{ get_option('loan_approved_message') }}</textarea>
												</div>
											</div>
											
											<div class="col-md-12">
												<div class="form-group">
													<button type="submit" class="btn btn-primary">{{ _lang('Save Template') }}</button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div> <!--End Loan Approved Tab-->
		
					</div>  
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>
@endsection
