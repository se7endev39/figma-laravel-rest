<form method="post" class="ajax-submit" autocomplete="off" action="{{ action('StaffController@update', $id) }}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">				
	
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
		<label class="control-label">{{ _lang('User Type') }}</label>						
		<select class="form-control select2" name="user_type" id="user_type" required>
		  <option value="manager">{{ _lang('Manager').' ('._lang('Limited Access').')' }}</option>
		  <option value="admin">{{ _lang('Admin') }}</option>
		</select>
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
$("#user_type").val("{{ $user->user_type }}");
</script>
