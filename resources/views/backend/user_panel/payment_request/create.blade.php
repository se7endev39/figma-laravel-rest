@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="plus-circle"></i></div>
				<span>{{ _lang('Create Payment Request') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-6">
			<div class="card">
			<div class="card-content">
				<div class="card-body">
				  <h4 class="card-title panel-title">{{ _lang('Create Payment Request') }}</h4>
				  <form method="post" class="validate" autocomplete="off" action="{{ route('payment_requests.store') }}" enctype="multipart/form-data">
					   {{ csrf_field() }}
					    <div class="row">
						    <div class="col-md-12">
							  <div class="form-group">
								<label class="control-label">{{ _lang('Recipients Email') }}</label>						
								<input type="email" class="form-control" name="recipients_email" value="{{ old('recipients_email') }}" required>
							  </div>
							</div>
							
							
							<div class="col-md-12">
							  <div class="form-group">
								<label class="control-label">{{ _lang('Credit Account') }}</label>						
								<select class="form-control select2" name="account_id" required>
									@foreach(\App\Account::where('user_id',Auth::id())->where('status',1)->get() as $credit_account )
										<option value="{{ $credit_account->id }}">{{ $credit_account->account_number.' - '.$credit_account->account_type->account_type.' ('.$credit_account->account_type->currency->name.')' }}</option>
									@endforeach
								</select>
							  </div>
							</div>

							<div class="col-md-12">
							  <div class="form-group">
								<label class="control-label">{{ _lang('Amount') }}</label>						
								<input type="text" class="form-control float-field" name="amount" value="{{ old('amount') }}" required>
							  </div>
							</div>

							<div class="col-md-12">
							  <div class="form-group">
								<label class="control-label">{{ _lang('Description') }}</label>						
								<textarea class="form-control" name="description" required>{{ old('description') }}</textarea>
							  </div>
							</div>

							<div class="col-md-12">
							  <div class="form-group">
								<button type="submit" class="btn btn-primary">{{ _lang('Send Payment Request') }}</button>
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
@endsection
