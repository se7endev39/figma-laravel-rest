@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="edit"></i></div>
				<span>{{ _lang('Update User') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card">		
				<div class="card-body">
				<h4 class="card-title panel-title">{{ _lang('Update User') }}</h4>

				<form method="post" class="validate" autocomplete="off" action="{{ action('StaffController@update', $id) }}" enctype="multipart/form-data">
					<div class="row">
						<div class="col-md-6">
							{{ csrf_field()}}
							<input name="_method" type="hidden" value="PATCH">				
							
							<div class="form-group">
								<label class="control-label">{{ _lang('First Name') }}</label>						
								<input type="text" class="form-control" name="first_name" value="{{ $user->first_name }}" required>
							</div>
							
							<div class="form-group">
								<label class="control-label">{{ _lang('Last Name') }}</label>						
								<input type="text" class="form-control" name="last_name" value="{{ $user->last_name }}" required>
							</div>

							<div class="form-group">
								<label class="control-label">{{ _lang('Email') }}</label>						
								<input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
							</div>
							
							<div class="form-group">
								<label class="control-label">{{ _lang('Phone') }}</label>						
								<input type="tel" class="form-control telephone" name="phone" value="{{ $user->phone }}" required>
							</div>

							<div class="form-group">
								<label class="control-label">{{ _lang('Password') }}</label>						
								<input type="password" class="form-control" name="password" value="">
							</div>
							
							<div class="form-group">
								<label class="control-label">{{ _lang('Confirm Password') }}</label>						
								<input type="password" class="form-control" name="password_confirmation" value="">
							</div>

							<div class="form-group">
								<label class="control-label">{{ _lang('User Type') }}</label>						
								<select class="form-control select2" name="user_type" id="user_type" required>
								<option value="manager">{{ _lang('Manager').' ('._lang('Limited Access').')' }}</option>
								<option value="admin">{{ _lang('Admin') }}</option>
								</select>
							</div>
							
							<div class="form-group">
								<label class="control-label">{{ _lang('Status') }}</label>						
								<select class="form-control select2" id="status" name="status" required>
								<option value="1">{{ _lang('Active') }}</option>
								<option value="0">{{ _lang('Inactive') }}</option>
								</select>
							</div>
							
							<div class="form-group">
								<button type="submit" class="btn btn-success">{{ _lang('Update') }}</button>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Profile Picture') }} ( 300 X 300 {{ _lang('for better view') }} )</label>						
								<input type="file" class="dropify" name="profile_picture" data-allowed-file-extensions="png jpg jpeg PNG JPG JPEG" data-default-file="{{ $user->profile_picture != "" ? asset('uploads/profile/'.$user->profile_picture) : '' }}" >
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
$("#user_type").val("{{ $user->user_type }}");
$("#status").val("{{ $user->status }}");
</script>
@endsection

