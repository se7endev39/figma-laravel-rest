<form method="post" class="ajax-submit" autocomplete="off" action="{{action('AccountTypeController@update', $id)}}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">				
	
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Account Type') }}</label>						
				<input type="text" class="form-control" name="account_type" value="{{ $accounttype->account_type }}" required>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Currency') }}</label>						
				<select class="form-control select2" name="currency_id" required>
	                 <option value="">{{ _lang('Select Currency') }}</option>
	                 {{ create_option('currency', 'id', 'name', $accounttype->currency_id, array('status=' => 1)) }}
			    </select>		
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Maintenance Fee (Annually)') }}</label>						
				<input type="text" class="form-control float-field" name="maintenance_fee" value="{{ $accounttype->maintenance_fee }}" required>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label">{{ _lang('Interest Rate') }} %</label>						
				<input type="text" class="form-control float-field" name="interest_rate" value="{{ $accounttype->interest_rate }}" required>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label">{{ _lang('Interest Period') }}</label>						
				<select class="form-control" name="interest_period" required>
	                 <option value="annually" {{ $accounttype->interest_period == 'annually' ? 'selected' : ''}}>{{ _lang('Annually') }}</option>
	                 <option value="monthly" {{ $accounttype->interest_period == 'monthly' ? 'selected' : ''}}>{{ _lang('Monthly') }}</option>
	                 <option value="quarterly" {{ $accounttype->interest_period == 'quarterly' ? 'selected' : ''}}>{{ _lang('Quarterly') }}</option>
				</select>	
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label">{{ _lang('Payout Period') }}</label>						
				<select class="form-control" name="payout_period" required>
	                 <option value="annually" {{ $accounttype->payout_period == 'annually' ? 'selected' : ''}}>{{ _lang('Annually') }}</option>
	                 <option value="monthly" {{ $accounttype->payout_period == 'monthly' ? 'selected' : ''}}>{{ _lang('Monthly') }}</option>
	                 <option value="quarterly" {{ $accounttype->payout_period == 'quarterly' ? 'selected' : ''}}>{{ _lang('Quarterly') }}</option>
				</select>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label">{{ _lang('Auto Create') }}</label>						
				<select class="form-control" name="auto_create" value="{{ old('auto_create') }}" required>
					<option value="0" {{ $accounttype->auto_create == '0' ? 'selected' : '' }}>{{ _lang('No') }}</option>
					<option value="1" {{ $accounttype->auto_create == '1' ? 'selected' : '' }}>{{ _lang('Yes') }}</option>
				</select>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Description') }}</label>						
				<textarea class="form-control" name="description">{{ $accounttype->description }}</textarea>
			</div>
		</div>

		
		<div class="form-group">
			<div class="col-md-12">
				<button type="submit" class="btn btn-primary">{{ _lang('Update') }}</button>
			</div>
		</div>
	</div>
</form>

