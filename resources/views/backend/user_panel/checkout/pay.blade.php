@extends('layouts.app')

@section('content')

<div class="container-fluid mt-10">
	<div class="row">
		<div class="col-lg-6 offset-lg-3">
			
			@if(Auth::user()->email == request()->business_email)
				<div class="alert alert-danger">
				   <button type="button" class="close" data-dismiss="alert">&times;</button>
	               <strong>{{ _lang('This Payment Request made by you. You cannot pay against your own payment request !') }}</strong>
				</div>	
			@endif
			
			<div class="card">
				<div class="card-body">
					<h4 class="card-title panel-title">{{ _lang('Make Payment') }}</h4>
					<form method="post" class="validate" autocomplete="off" action="{{ route('checkout') }}">
						{{ csrf_field() }}
						<div class="row">
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
									<label class="control-label">{{ _lang('Item Number') }}</label>						
									<input type="text" class="form-control" name="item_number" value="{{ request()->item_number }}" readonly="true">
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label">{{ _lang('Item Name') }}</label>						
									<input type="text" class="form-control" name="item_name" value="{{ request()->item_name }}" readonly="true">
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label">{{ _lang('Amount').' ('.currency_by_account_number(request()->account_number).')' }}</label>						
									<input type="text" class="form-control" name="amount" value="{{ request()->amount }}" readonly="true">
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label">{{ _lang('Fee').' ('.currency_by_account_number(request()->account_number).')' }}</label>						
									<input type="text" class="form-control" name="fee" value="{{ generate_fee( request()->amount, get_option('payment_fee',0), get_option('payment_fee_type','fixed') ) }}" readonly="true">
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label">{{ _lang('Item Description') }}</label>						
									<textarea class="form-control" name="item_description" readonly="true">{{ request()->item_description }}</textarea>
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group">
									<button type="submit" class="btn btn-primary">{{ _lang('Make Payment') }}</button>
									<a href="{{ session('cancel_url') }}" class="btn btn-danger">{{ _lang('Cancel Payment') }}</a>
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
