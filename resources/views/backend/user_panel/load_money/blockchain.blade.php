@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="arrow-right-circle"></i></div>
				<span>{{ _lang('BlockChain Deposit') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-md-6">

			@if(Session::has('success'))
				<div class="alert alert-success">
				   <button type="button" class="close" data-dismiss="alert">&times;</button>
	               <strong>{{ session('success') }}</strong>
				</div>	
			@endif

			<div class="card">
				<div class="card-body">
					<h4 class="card-title panel-title">{{ _lang('Load Money Using BlockChain') }}</h4>
					<form method="post" class="validate" autocomplete="off" action="{{ url('user/load_money/blockchain') }}">
						{{ csrf_field() }}
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label">{{ _lang('Credit Account') }}</label>						
									<select class="form-control select2" name="credit_account" required="true" {{ $method == 'PayPal' ? 'disabled' : '' }}>
										@foreach(\App\Account::where('user_id',Auth::id())->where('status',1)->get() as $account )
											<option value="{{ $account->id }}" {{ $credit_account == $account->id ? 'selected' : '' }}>{{ $account->account_number.' - '.$account->account_type->account_type.' ('.$account->account_type->currency->name.')' }}</option>
										@endforeach
									</select>
								</div>
							</div>

							@if($method == '')
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Amount') }}</label>								
										<input type="text" class="form-control float-field" name="amount" value="{{ old('amount',$amount) }}" required="true" {{ $method == 'PayPal' ? 'disabled' : '' }}>
									</div>
								</div>
							
								<div class="col-md-12">
									<div class="form-group">
										<button type="submit" class="btn btn-primary">{{ _lang('Process Via BlockChain') }}</button>
									</div>
								</div>
							@elseif($method == 'BlockChain')
							    <div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Amount') }}</label>								
										<div class="input-group mb-3">	
											<div class="input-group-prepend">
												<span class="input-group-text">{{ account_currency($credit_account) }}</span>
											</div>
											<input type="text" class="form-control float-field" name="amount" value="{{ decimalPlace($amount) }}" disabled>
									    </div>
									</div>
								</div>
								
							    <div class="col-md-12">
									<div class="form-group">
										<label class="control-label"><b>{{ _lang('Send Exact Amount') }}</b></label>								
										<div class="input-group mb-3">
											<div class="input-group-prepend">
												<span class="input-group-text">BTC</span>
											</div>
											<input type="text" class="form-control float-field" value="{{ $converted_amount }}" disabled>
										</div>
									</div>
								</div>
								
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('BTC Address') }}</label><br>								
										<h6><span class="text-green">{{ $btc_address }}</span></h6>
									</div>
								</div>
								
								<div class="col-md-12">
									<div class="form-group">
										<img src="{{ $qr_code }}" class="border border-primary rounded"/>
									</div>
								</div>
  		                    @endif
						</div>			
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

