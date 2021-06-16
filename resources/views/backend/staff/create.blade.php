@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="user"></i></div>
				<span>{{ _lang('Create User') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
<div class="row">
    <div class="col-12">
	  <div class="card">
		<div class="card-body">
		  <h4 class="card-title panel-title">{{ _lang('Create User') }}</h4>
		  <form method="post" class="validate" autocomplete="off" action="{{ route('staffs.store') }}" enctype="multipart/form-data">
		    <div class="row">
				<div class="col-md-6">
					{{ csrf_field() }}
					  <div class="col-md-12">
						  <div class="form-group">
							<label class="control-label">{{ _lang('First Name') }}</label>						
							<input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required>
						  </div>
					  </div>
					  
					  <div class="col-md-12">
						  <div class="form-group">
							<label class="control-label">{{ _lang('Last Name') }}</label>						
							<input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required>
						  </div>
					  </div>

					  <div class="col-md-12">
						  <div class="form-group">
							<label class="control-label">{{ _lang('Email') }}</label>						
							<input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
						  </div>
					  </div>
					  
					  <div class="col-md-12">
						  <div class="form-group">
							<label class="control-label">{{ _lang('Phone') }}</label>						
							<input type="tel" class="form-control telephone" name="phone" value="{{ old('phone','+1') }}" required>
						  </div>
					  </div>

					  <div class="col-md-12">
						  <div class="form-group">
							<label class="control-label">{{ _lang('Password') }}</label>						
							<input type="password" class="form-control" name="password" value="{{ old('password') }}" required>
						  </div>
					  </div>
					
					<div class="col-md-12">
						 <div class="form-group">
							<label class="control-label">{{ _lang('Confirm Password') }}</label>						
							<input type="password" class="form-control" name="password_confirmation" required>
						 </div>
					 </div>

					<div class="col-md-12">
					  <div class="form-group">
						<label class="control-label">{{ _lang('User Type') }}</label>						
						<select class="form-control select2" id="user-type" name="user_type" required>
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
							<button type="submit" class="btn btn-success">{{ _lang('Save') }}</button>
							<button type="reset" class="btn btn-danger">{{ _lang('Reset') }}</button>	
						</div>
					</div>	
				</div>
				
				<div class="col-md-6">		
					<div class="col-md-12">					 
						<div class="form-group">
							<label class="control-label">{{ _lang('Profile Picture') }} ( 300 X 300 {{ _lang('for better view') }} )</label>						
							<input type="file" class="dropify" name="profile_picture" data-allowed-file-extensions="png jpg jpeg PNG JPG JPEG" data-default-file="">
						</div>
					</div>	
				</div>	
			</div>
		  </form>
		 
		</div>
	  </div>
	</div>
  </div>
</div>
@endsection

@section('js-script')
<script>
$("#user_type").val("{{ old('user_type') }}");
</script>
@endsection


