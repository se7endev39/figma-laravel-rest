@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="plus-circle"></i></div>
				<span>{{ _lang('Make Payment') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			@if(Session::has('success'))
				<div class="alert alert-success">
				   <button type="button" class="close" data-dismiss="alert">&times;</button>
	               <strong>{{ session('success') }}</strong>
				</div>	
			@endif
			
			@if(Auth::id() == $paymentrequest->created_by)
				<div class="alert alert-danger">
				   <button type="button" class="close" data-dismiss="alert">&times;</button>
	               <strong>{{ _lang('This Payment Request made by you. You cannot pay against your own payment request !') }}</strong>
				</div>	
			@endif
			
			<div class="card">
			<div class="card-content">
				<div class="card-body">
				  <h4 class="card-title panel-title">{{ _lang('Make Payment') }}</h4>
				  <form method="post" class="validate" autocomplete="off" action="{{ url('user/payment_request/pay/' . $paymentrequest->id) }}" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="row">
						<div class="col-md-5">
							<div class="col-md-12">
							  <div class="form-group">
								<label class="control-label">{{ _lang('Select Account') }}</label>						
								<select class="form-control select2" name="debit_account" required>
									<option value="">{{ _lang('Select Account') }}</option>
									@foreach(\App\Account::where('user_id',Auth::id())->where('status',1)->get() as $account )
										<option value="{{ $account->id }}">{{ $account->account_number.' - '.$account->account_type->account_type.' ('.$account->account_type->currency->name.')' }}</option>
									@endforeach
								</select>
							  </div>
							</div>

							<div class="col-md-12">
							  <div class="form-group">
								<label class="control-label">{{ _lang('Amount') }}</label>						
								<input type="text" class="form-control" name="amount" value="{{ $paymentrequest->account->account_type->currency->name.' '.$paymentrequest->amount }}" readonly="true">
							  </div>
							</div>
							
							<div class="col-md-12">
							  <div class="form-group">
								<label class="control-label">{{ _lang('Description') }}</label>						
								<textarea class="form-control" name="description" readonly="true">{{ $paymentrequest->description }}</textarea>
							  </div>
							</div>

							<div class="col-md-12">
							  <div class="form-group">
								<button type="submit" class="btn btn-primary">{{ _lang('Make Payment') }}</button>
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
</div>
@endsection
