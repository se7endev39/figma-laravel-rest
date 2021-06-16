@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="plus-circle"></i></div>
				<span>{{ _lang('Create Account Type') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<form method="post" class="validate" autocomplete="off" action="{{ route('account_types.store') }}">
		<div class="row">	
			<div class="col-lg-6">
				<div class="card">
				
					<div class="card-header d-flex justify-content-between align-items-center">
						<span class="panel-title">{{ _lang('Create Account Type') }}</span>
					</div>
					
					<div class="card-body">	
						{{ csrf_field() }}
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label">{{ _lang('Account Type') }}</label>						
									<input type="text" class="form-control" name="account_type" value="{{ old('account_type') }}" required>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ _lang('Currency') }}</label>						
									<select class="form-control select2" name="currency_id" required>
										 <option value="">{{ _lang('Select Currency') }}</option>
										 {{ create_option('currency', 'id', 'name', old('currency_id'), array('status =' => 1)) }}
									</select>		
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ _lang('Maintenance Fee (Annually)') }}</label>						
									<input type="text" class="form-control float-field" name="maintenance_fee" value="{{ old('maintenance_fee') }}" required>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ _lang('Interest Rate') }} %</label>						
									<input type="text" class="form-control float-field" name="interest_rate" value="{{ old('interest_rate') }}" required>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ _lang('Interest Period') }}</label>						
									<select class="form-control" name="interest_period" required>
										 <option value="annually" {{ old('interest_period') == 'annually' ? 'selected' : ''}}>{{ _lang('Annually') }}</option>
										 <option value="monthly" {{ old('interest_period') == 'monthly' ? 'selected' : ''}}>{{ _lang('Monthly') }}</option>
										 <option value="quarterly" {{ old('interest_period') == 'quarterly' ? 'selected' : ''}}>{{ _lang('Quarterly') }}</option>
									</select>	
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ _lang('Payout Period') }}</label>						
									<select class="form-control" name="payout_period" required>
										 <option value="annually" {{ old('payout_period') == 'annually' ? 'selected' : ''}}>{{ _lang('Annually') }}</option>
										 <option value="monthly" {{ old('payout_period') == 'monthly' ? 'selected' : ''}}>{{ _lang('Monthly') }}</option>
										 <option value="quarterly" {{ old('payout_period') == 'quarterly' ? 'selected' : ''}}>{{ _lang('Quarterly') }}</option>
									</select>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ _lang('Auto Create') }}</label>						
									<select class="form-control" name="auto_create" value="{{ old('auto_create') }}" required>
										<option value="0" {{ old('auto_create') == '0' ? 'selected' : '' }}>{{ _lang('No') }}</option>
										<option value="1" {{ old('auto_create') == '1' ? 'selected' : '' }}>{{ _lang('Yes') }}</option>
									</select>
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label">{{ _lang('Description') }}</label>						
									<textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
								</div>
							</div>


							<div class="col-md-12">
								<div class="form-group">
									<button type="reset" class="btn btn-danger">{{ _lang('Reset') }}</button>
									<button type="submit" class="btn btn-primary">{{ _lang('Save') }}</button>
								</div>
							</div>
						</div>		
					</div>
				</div>
			</div>
			
			<div class="col-lg-6">
				<div class="card">
					
					<div class="card-header d-flex justify-content-between align-items-center">
						<span class="panel-title">{{ _lang('Transactions Fees') }}</span>
					</div>
					
					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ _lang('Transfer Between Accounts Fee') }}</label>						
									<input type="text" class="form-control float-field" name="tba_fee" value="{{ old('tba_fee',0) }}" required>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ _lang('Transfer Between Accounts Fee Type') }}</label>						
									<select class="form-control" name="tba_fee_type" required>
										<option value="fixed" {{ old( 'tba_fee_type' ) == 'fixed' ? 'selected' : '' }}>{{ _lang('Fixed') }}</option>
										<option value="percent" {{ old( 'tba_fee_type' ) == 'percent' ? 'selected' : '' }}>{{ _lang('Percent') }}</option>
									</select>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ _lang('Transfer Between Users Fee') }}</label>						
									<input type="text" class="form-control float-field" name="tbu_fee" value="{{ old('tbu_fee',0) }}" required>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ _lang('Transfer Between Users Fee Type') }}</label>						
									<select class="form-control" name="tbu_fee_type" required>
										<option value="fixed" {{ old('tbu_fee_type')=='fixed' ? 'selected' : '' }}>{{ _lang('Fixed') }}</option>
										<option value="percent" {{ old('tbu_fee_type')=='percent' ? 'selected' : '' }}>{{ _lang('Percent') }}</option>
									</select>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ _lang('Card Funding Transfer') }}</label>						
									<input type="text" class="form-control float-field" name="cft_fee" value="{{ old('cft_fee',0) }}" required>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ _lang('Card Funding Transfer Fee Type') }}</label>						
									<select class="form-control" name="cft_fee_type" required>
										<option value="fixed" {{ old('cft_fee_type')=='fixed' ? 'selected' : '' }}>{{ _lang('Fixed') }}</option>
										<option value="percent" {{ old('cft_fee_type')=='percent' ? 'selected' : '' }}>{{ _lang('Percent') }}</option>
									</select>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ _lang('Outgoing Wire Transfer Fee') }}</label>						
									<input type="text" class="form-control float-field" name="owt_fee" value="{{ old('owt_fee',0) }}" required>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ _lang('Outgoing Wire Transfer Fee Type') }}</label>						
									<select class="form-control" name="owt_fee_type" required>
										<option value="fixed" {{ old('owt_fee_type')=='fixed' ? 'selected' : '' }}>{{ _lang('Fixed') }}</option>
										<option value="percent" {{ old('owt_fee_type')=='percent' ? 'selected' : '' }}>{{ _lang('Percent') }}</option>
									</select>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ _lang('Incoming Wire Transfer Fee') }}</label>						
									<input type="text" class="form-control float-field" name="iwt_fee" value="{{ old('iwt_fee',0) }}" required>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ _lang('Incoming Wire Transfer Fee Type') }}</label>						
									<select class="form-control" name="iwt_fee_type" required>
										<option value="fixed" {{ old('iwt_fee_type')=='fixed' ? 'selected' : '' }}>{{ _lang('Fixed') }}</option>
										<option value="percent" {{ old('iwt_fee_type')=='percent' ? 'selected' : '' }}>{{ _lang('Percent') }}</option>
									</select>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ _lang('Payment Fee') }}</label>						
									<input type="text" class="form-control float-field" name="payment_fee" value="{{ old('payment_fee',0) }}" required>
								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">{{ _lang('Payment Fee Type') }}</label>						
									<select class="form-control" name="payment_fee_type" required>
										<option value="fixed" {{ old('payment_fee_type')=='fixed' ? 'selected' : '' }}>{{ _lang('Fixed') }}</option>
										<option value="percent" {{ old('payment_fee_type')=='percent' ? 'selected' : '' }}>{{ _lang('Percent') }}</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>	
</div>
@endsection


