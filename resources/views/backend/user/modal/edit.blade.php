<form method="post" class="ajax-submit" autocomplete="off" action="{{ action('UserController@update', $id) }}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">				
	
	 <div class="col-md-12">
	  <div class="form-group">
		<label class="control-label">{{ _lang('Account Type') }}</label>						
		<select class="form-control select2" id="account_type" name="account_type" required>
		  <option value="personal">{{ _lang('Personal') }}</option>
		  <option value="business">{{ _lang('Business') }}</option>
		</select>
	  </div>
	 </div>
	
	 <div class="col-md-12">
		 <div class="form-group">
			<label class="control-label">{{ _lang('First Name') }}</label>						
			<input type="text" class="form-control" name="first_name" value="{{ $user->first_name }}" required>
		 </div>
	 </div>
	 
	 <div class="col-md-12">
		 <div class="form-group">
			<label class="control-label">{{ _lang('Last Name') }}</label>						
			<input type="text" class="form-control" name="last_name" value="{{ $user->last_name }}" required>
		 </div>
	 </div>
	 
	 <div class="col-md-12{{ $user->account_type == 'business' ? '' : ' d-none' }}" id="business_name">
		<div class="form-group">
			<label class="control-label">{{ _lang('Business Name') }}</label>						
			<input type="text" class="form-control" name="business_name" value="{{ $user->business_name }}">
		</div>
	 </div>

	 <div class="col-md-12">
		 <div class="form-group">
			<label class="control-label">{{ _lang('Email') }}</label>						
			<input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
		 </div>
	 </div>
	 
	 <div class="col-md-12">
		 <div class="form-group">
			<label class="control-label">{{ _lang('Phone') }}</label>						
			<input type="tel" class="form-control telephone" name="phone" value="{{ $user->phone }}" required>
		 </div>
	 </div>

	<div class="col-md-12">
	 <div class="form-group">
		<label class="control-label">{{ _lang('Password') }}</label>						
		<input type="password" class="form-control" name="password">
	 </div>
	</div>
	
	<div class="col-md-12">
	 <div class="form-group">
		<label class="control-label">{{ _lang('Confirm Password') }}</label>						
		<input type="password" class="form-control" name="password_confirmation">
	 </div>
	</div>
	
	<div class="col-md-12">
	  <div class="form-group">
		<label class="control-label">{{ _lang('Status') }}</label>						
		<select class="form-control select2" id="status" name="status" required>
		  <option value="1">{{ _lang('Active') }}</option>
		  <option value="0">{{ _lang('Inactive') }}</option>
		</select>
	  </div>
	</div>
	
	<div class="col-md-12">
	  <div class="form-group">
		<label class="control-label">{{ _lang('Account Status') }}</label>						
		<select class="form-control select2" id="account_status" name="account_status" required>
		  <option value="Verified">{{ _lang('Verified') }}</option>
		  <option value="Unverified">{{ _lang('Unverified') }}</option>
		</select>
	  </div>
	</div>

	<div class="col-md-6">
	  <div class="form-group">
		<label class="control-label">{{ _lang('Date Of Birth') }}</label>						
		<input type="text" class="form-control datepicker" name="date_of_birth" value="{{ $user->date_of_birth }}">
	  </div>
	</div>

	<div class="col-md-6">
	  <div class="form-group">
		<label class="control-label">{{ _lang('Passport') }}</label>						
		<input type="text" class="form-control" name="passport" value="{{ $user->passport }}">
	  </div>
	</div>

	<div class="col-md-6">
	  <div class="form-group">
		<label class="control-label">{{ _lang('Country Of Residence') }}</label>						
		<select class="form-control select2" name="country_of_residence">
			<option value="">{{ _lang('Country Of Citizenship') }}</option>
            {{ get_country_list($user->country_of_residence) }}
		</select>
	  </div>
	</div>

	<div class="col-md-6">
	  <div class="form-group">
		<label class="control-label">{{ _lang('Country Of Citizenship') }}</label>						
		<select class="form-control select2" name="country_of_citizenship">
			<option value="">{{ _lang('Country Of Citizenship') }}</option>
            {{ get_country_list($user->country_of_citizenship) }}
		</select>
	  </div>
	</div>

	<div class="col-md-12">
	  <div class="form-group">
		<label class="control-label">{{ _lang('Address') }}</label>						
		<textarea class="form-control" name="address">{{ $user->address }}</textarea>
	  </div>
	</div>

	<div class="col-md-12">
	  <div class="form-group">
		<label class="control-label">{{ _lang('City') }}</label>						
		<input type="text" class="form-control" name="city" value="{{ $user->city }}">
	  </div>
	</div>

	<div class="col-md-12">
	  <div class="form-group">
		<label class="control-label">{{ _lang('State') }}</label>						
		<input type="text" class="form-control" name="state" value="{{ $user->state }}">
	  </div>
	</div>

	<div class="col-md-12">
	  <div class="form-group">
		<label class="control-label">{{ _lang('Zip') }}</label>						
		<input type="text" class="form-control" name="zip" value="{{ $user->zip }}">
	  </div>
	</div>
	
	<div class="col-md-12">
	 <div class="form-group">
		<label class="control-label">{{ _lang('Profile Picture') }} ( 300 X 300 {{ _lang('for better view') }} )</label>						
		<input type="file" class="dropify" name="profile_picture" data-allowed-file-extensions="png jpg jpeg PNG JPG JPEG" data-default-file="{{ $user->profile_picture != "" ? asset('uploads/profile/'.$user->profile_picture) : '' }}" >
	 </div>
	</div>

				
	<div class="form-group">
	  <div class="col-md-12">
		<button type="submit" class="btn btn-primary">{{ _lang('Update') }}</button>
	  </div>
	</div>
</form>

<script>
$("#account_type").val("{{ $user->account_type }}");
$("#status").val("{{ $user->status }}");
$("#account_status").val("{{ $user->account_status }}");
$(document).on('change','#account_type',function(){
	if($(this).val() == 'business'){
		$("#business_name").removeClass('d-none');
	}else{
		$("#business_name").addClass('d-none');
	}
});
</script>
