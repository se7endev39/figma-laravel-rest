@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="plus-circle"></i></div>
				<span>{{ _lang('Create Account') }}</span>
			</h1>
		</div>
	</div>
</div>


<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-6">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">{{ _lang('Create Account') }}</h4>
					<form method="post" class="validate" autocomplete="off" action="{{ route('accounts.store') }}" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="row">
							<div class="col-lg-12">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Account Number') }}</label>						
										<input type="text" class="form-control" name="account_number" value="{{ new_account_number() }}" required>
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Account Owner') }}</label>						
										<select class="form-control select2" name="user_id" required>
							                <option value="">{{ _lang('Select User') }}</option>
							                @foreach ( \App\User::where('status',1)->where('user_type','user')->get() as $user )
												<option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->first_name.' '.$user->last_name }}</option>
							                @endforeach
										</select>	
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Account Type') }}</label>						
										<select class="form-control select2" name="account_type_id" required>
							                 <option value="">{{ _lang('Select Type') }}</option>
							                 @foreach ( \App\AccountType::all() as $account_type )
												<option value="{{ $account_type->id }}" {{ old('account_type_id') == $account_type->id ? 'selected' : '' }}>{{ $account_type->account_type.' ('.$account_type->currency->name.')' }}</option>
							                 @endforeach
										</select>	
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Status') }}</label>						
										<select class="form-control" name="status" required>
							                 <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>{{ _lang('Active') }}</option>
							                 <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>{{ _lang('Blocked') }}</option>
										</select>	
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Opening Balance') }}</label>						
										<input type="text" class="form-control float-field" name="opening_balance" value="{{ old('opening_balance') }}" required>
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Description') }}</label>						
										<textarea class="form-control" name="description">{{ old('description') }}</textarea>
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
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection


