@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="plus-circle"></i></div>
				<span>{{ _lang('Loan Repayment') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-6">
			<div class="card">
				<div class="card-header">
					<span class="panel-title">{{ _lang('Loan Repayment') }}</span>
				</div>
				<div class="card-body">
				    <form method="post" class="validate" autocomplete="off" action="{{ url('user/loans/payment/'.$loan->id) }}">
						{{ csrf_field() }}
						<div class="row">
						    <div class="col-md-12">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Loan ID') }}</label>						
								    <input type="text" class="form-control" name="loan_id" value="{{ $loan->loan_id }}" readonly="true" required>
						        </div>
						    </div>

						    <div class="col-md-12">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Due Payment Of') }}</label>						
								    <input type="text" class="form-control" name="due_amount_of" value="{{ date('d/M/Y',strtotime($loan->next_payment->repayment_date)) }}" readonly="true">
						        </div>
						    </div>

						   	<div class="col-md-12">		
						   	 	<div class="form-group ">
									<label class="control-label">{{ _lang('Debit Account') }}</label>				
									<select class="form-control select2" name="account_id" id="account_id" required>
										<option value="">{{ _lang('Select Account') }}</option>
										@foreach(\App\Account::where('user_id',Auth::id())->where('status',1)->get() as $debit_account )
											<option value="{{ $debit_account->id }}">{{ $debit_account->account_number.' - '.$debit_account->account_type->account_type.' ('.$debit_account->account_type->currency->name.')' }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="col-md-12">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Late Penalties').' ( '._lang('It will Only apply if payment date is over') }} )</label>						
							        <div class="input-group">
								        <input type="text" class="form-control float-field" name="late_penalties" id="late_penalties" value="{{ $loan->next_payment->penalty }}" readonly="true">
								        <div class="input-group-append">
										    <span class="input-group-text currency"></span>
										</div>
									</div>
						        </div>
						    </div>

							<div class="col-md-12">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Amount To Pay') }}</label>						
							        <div class="input-group">
								        <input type="text" class="form-control float-field" name="amount_to_pay" id="amount_to_pay" value="{{ $loan->next_payment->amount_to_pay }}" readonly="true" required>
								        <div class="input-group-append">
										    <span class="input-group-text currency"></span>
										</div>
									</div>
						        </div>
						    </div>

							<div class="col-md-12">
							    <div class="form-group">
								    <label class="control-label">{{ _lang('Remarks') }}</label>						
								    <textarea class="form-control" name="remarks">{{ old('remarks') }}</textarea>
							    </div>
							</div>
								
							<div class="col-md-12">
								<div class="form-group">
									<button type="submit" class="btn btn-primary">{{ _lang('Make Payment') }}</button>
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

