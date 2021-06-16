@extends('layouts.app')

@section('content')

<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="settings"></i></div>
				<span>{{ _lang('Fee Settings') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="card">
						<div class="card-body">
							<h4 class="card-title panel-title">{{ _lang('Fee Settings') }}</h4>
							<form method="post" class="appsvan-submit params-panel" autocomplete="off" action="{{ url('admin/administration/general_settings/update') }}" enctype="multipart/form-data">
								{{ csrf_field() }}
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">{{ _lang('Transfer Between Accounts Fee') }}</label>						
											<input type="text" class="form-control float-field" name="tba_fee" value="{{ get_option('tba_fee',0) }}" required>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">{{ _lang('Transfer Between Accounts Fee Type') }}</label>						
											<select class="form-control" name="tba_fee_type" required>
												<option value="fixed" {{ get_option( 'tba_fee_type' ) == 'fixed' ? 'selected' : '' }}>{{ _lang('Fixed') }}</option>
												<option value="percent" {{ get_option( 'tba_fee_type' ) == 'percent' ? 'selected' : '' }}>{{ _lang('Percent') }}</option>
											</select>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">{{ _lang('Transfer Between Users Fee') }}</label>						
											<input type="text" class="form-control float-field" name="tbu_fee" value="{{ get_option('tbu_fee',0) }}" required>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">{{ _lang('Transfer Between Users Fee Type') }}</label>						
											<select class="form-control" name="tbu_fee_type" required>
												<option value="fixed" {{ get_option('tbu_fee_type')=='fixed' ? 'selected' : '' }}>{{ _lang('Fixed') }}</option>
												<option value="percent" {{ get_option('tbu_fee_type')=='percent' ? 'selected' : '' }}>{{ _lang('Percent') }}</option>
											</select>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">{{ _lang('Card Funding Transfer') }}</label>						
											<input type="text" class="form-control float-field" name="cft_fee" value="{{ get_option('cft_fee',0) }}" required>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">{{ _lang('Card Funding Transfer Fee Type') }}</label>						
											<select class="form-control" name="cft_fee_type" required>
												<option value="fixed" {{ get_option('cft_fee_type')=='fixed' ? 'selected' : '' }}>{{ _lang('Fixed') }}</option>
												<option value="percent" {{ get_option('cft_fee_type')=='percent' ? 'selected' : '' }}>{{ _lang('Percent') }}</option>
											</select>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">{{ _lang('Outgoing Wire Transfer Fee') }}</label>						
											<input type="text" class="form-control float-field" name="owt_fee" value="{{ get_option('owt_fee',0) }}" required>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">{{ _lang('Outgoing Wire Transfer Fee Type') }}</label>						
											<select class="form-control" name="owt_fee_type" required>
												<option value="fixed" {{ get_option('owt_fee_type')=='fixed' ? 'selected' : '' }}>{{ _lang('Fixed') }}</option>
												<option value="percent" {{ get_option('owt_fee_type')=='percent' ? 'selected' : '' }}>{{ _lang('Percent') }}</option>
											</select>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">{{ _lang('Incoming Wire Transfer Fee') }}</label>						
											<input type="text" class="form-control float-field" name="iwt_fee" value="{{ get_option('iwt_fee',0) }}" required>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">{{ _lang('Incoming Wire Transfer Fee Type') }}</label>						
											<select class="form-control" name="iwt_fee_type" required>
												<option value="fixed" {{ get_option('iwt_fee_type')=='fixed' ? 'selected' : '' }}>{{ _lang('Fixed') }}</option>
												<option value="percent" {{ get_option('iwt_fee_type')=='percent' ? 'selected' : '' }}>{{ _lang('Percent') }}</option>
											</select>
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">{{ _lang('Payment Fee') }}</label>						
											<input type="text" class="form-control float-field" name="payment_fee" value="{{ get_option('payment_fee',0) }}" required>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">{{ _lang('Payment Fee Type') }}</label>						
											<select class="form-control" name="payment_fee_type" required>
												<option value="fixed" {{ get_option('payment_fee_type')=='fixed' ? 'selected' : '' }}>{{ _lang('Fixed') }}</option>
												<option value="percent" {{ get_option('payment_fee_type')=='percent' ? 'selected' : '' }}>{{ _lang('Percent') }}</option>
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
			</div>
		</div>
	</div>
</div>
@endsection

