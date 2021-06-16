<form method="post" class="ajax-submit" autocomplete="off" action="{{ route('users.store') }}" enctype="multipart/form-data">
	{{ csrf_field() }}
	
	<div class="row p-2">
		<div class="col-md-12">
		  <div class="form-group">
			<label class="control-label">{{ _lang('Account Type') }}</label>						
			<select class="form-control" id="account_type" name="account_type" required>
			  <option value="personal">{{ _lang('Personal') }}</option>
			  <option value="business">{{ _lang('Business') }}</option>
			</select>
		  </div>
		</div>
		
		<div class="col-md-6">
		  <div class="form-group">
			<label class="control-label">{{ _lang('First Name') }}</label>						
			<input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required>
		  </div>
		</div>
		
		<div class="col-md-6">
		  <div class="form-group">
			<label class="control-label">{{ _lang('Last Name') }}</label>						
			<input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required>
		  </div>
		</div>
		
		<div class="col-md-12 d-none" id="business_name">
			<div class="form-group">
				<label class="control-label">{{ _lang('Business Name') }}</label>						
				<input type="text" class="form-control" name="business_name" value="{{ old('business_name') }}">
			</div>
		</div>

		<div class="col-md-6">
		  <div class="form-group">
			<label class="control-label">{{ _lang('Email') }}</label>						
			<input type="email" class="form-control" name="email" value="{{ old('email') }}">
		  </div>
		</div>
		
		<div class="col-md-6">
		  <div class="form-group">
			<label class="control-label">{{ _lang('Phone') }}</label>						
			<input type="tel" class="form-control telephone" name="phone" value="{{ old('phone','+1') }}">
		  </div>
		</div>

		<div class="col-md-6">
		  <div class="form-group">
			<label class="control-label">{{ _lang('Password') }}</label>						
			<input type="password" class="form-control" name="password">
		  </div>
		</div>
		
		<div class="col-md-6">
		 <div class="form-group">
			<label class="control-label">{{ _lang('Confirm Password') }}</label>						
			<input type="password" class="form-control" name="password_confirmation" required>
		 </div>
		</div>
		
		<div class="col-md-6">
		  <div class="form-group">
			<label class="control-label">{{ _lang('Status') }}</label>						
			<select class="form-control" id="status" name="status" required>
			  <option value="1">{{ _lang('Active') }}</option>
			  <option value="0">{{ _lang('Inactive') }}</option>
			</select>
		  </div>
		</div>
		
		<div class="col-md-6">
		  <div class="form-group">
			<label class="control-label">{{ _lang('Account Status') }}</label>						
			<select class="form-control" id="account_status" name="account_status" required>
			  <option value="Unverified">{{ _lang('Unverified') }}</option>
			  <option value="Verified">{{ _lang('Verified') }}</option>
			</select>
		  </div>
		</div>

		<div class="col-md-6">
		  <div class="form-group">
			<label class="control-label">{{ _lang('Date Of Birth') }}</label>						
			<input type="text" class="form-control datepicker" name="date_of_birth" value="{{ old('date_of_birth') }}">
		  </div>
		</div>

		<div class="col-md-6">
		  <div class="form-group">
			<label class="control-label">{{ _lang('Passport') }}</label>						
			<input type="text" class="form-control" name="passport" value="{{ old('passport') }}">
		  </div>
		</div>

		<div class="col-md-6">
		  <div class="form-group">
			<label class="control-label">{{ _lang('Country Of Residence') }}</label>						
			<select class="form-control select2" name="country_of_residence">
				<option value="">{{ _lang('Country Of Citizenship') }}</option>
                {{ get_country_list(old('country_of_residence')) }}
			</select>
		  </div>
		</div>

		<div class="col-md-6">
		  <div class="form-group">
			<label class="control-label">{{ _lang('Country Of Citizenship') }}</label>						
			<select class="form-control select2" name="country_of_citizenship">
				<option value="">{{ _lang('Country Of Citizenship') }}</option>
                {{ get_country_list(old('country_of_citizenship')) }}
			</select>
		  </div>
		</div>

		<div class="col-md-12">
		  <div class="form-group">
			<label class="control-label">{{ _lang('Address') }}</label>						
			<textarea class="form-control" name="address">{{ old('address') }}</textarea>
		  </div>
		</div>

		<div class="col-md-12">
		  <div class="form-group">
			<label class="control-label">{{ _lang('City') }}</label>						
			<input type="text" class="form-control" name="city" value="{{ old('city') }}">
		  </div>
		</div>

		<div class="col-md-12">
		  <div class="form-group">
			<label class="control-label">{{ _lang('State') }}</label>						
			<input type="text" class="form-control" name="state" value="{{ old('state') }}">
		  </div>
		</div>

		<div class="col-md-12">
		  <div class="form-group">
			<label class="control-label">{{ _lang('Zip') }}</label>						
			<input type="text" class="form-control" name="zip" value="{{ old('zip') }}">
		  </div>
		</div>
		
		<div class="col-md-12">
		 <div class="form-group">
			<label class="control-label">{{ _lang('Profile Picture') }} ( 300 X 300 {{ _lang('for better view') }} )</label>						
			<input type="file" class="dropify" name="profile_picture" data-allowed-file-extensions="png jpg jpeg PNG JPG JPEG" data-default-file="">
		 </div>
		</div>
					
		<div class="col-md-12">
		  <div class="form-group">
		    <button type="reset" class="btn btn-danger">{{ _lang('Reset') }}</button>
			<button type="submit" class="btn btn-primary">{{ _lang('Save') }}</button>
		  </div>
		</div>
	</div>
</form>

<script>
$("#user_type").val("{{ old('user_type') }}");
$(document).on('change','#account_type',function(){
	if($(this).val() == 'business'){
		$("#business_name").removeClass('d-none');
	}else{
		$("#business_name").addClass('d-none');
	}
});
</script>