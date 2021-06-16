@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="plus-circle"></i></div>
				<span>{{ _lang('Add New Loan') }}</span>
			</h1>
		</div>
	</div>
</div>


<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-8">
			<div class="card">
				<div class="card-header">
					<span class="panel-title">{{ _lang('Add New Loan') }}</span>
				</div>
				<div class="card-body">
				    <form method="post" class="validate" autocomplete="off" action="{{ url('user/loans/apply_loan') }}" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="row">

							<div class="col-md-6">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Loan Product') }}</label>						
							        <select class="form-control auto-select" data-selected="{{ old('loan_product_id') }}" name="loan_product_id" required>
						                <option value="">{{ _lang('Select One') }}</option>
										{{ create_option('loan_products','id','name',old('loan_product_id')) }}
									</select>
								</div>
						    </div>


							<div class="col-md-6">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Account') }}</label>						
							        <select class="form-control auto-select" data-selected="{{ old('account_id') }}" id="account_id" name="account_id" required>
						                <option value="">{{ _lang('Select One') }}</option>
						                @foreach(\App\Account::where('user_id',Auth::id())->where('status',1)->get() as $debit_account )
											<option value="{{ $debit_account->id }}">{{ $debit_account->account_number.' - '.$debit_account->account_type->account_type.' ('.$debit_account->account_type->currency->name.')' }}</option>
										@endforeach
									</select>
								</div>
						    </div>

						    <div class="col-md-6">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('First Payment Date') }}</label>						
							        <input type="text" class="form-control datepicker" name="first_payment_date" value="{{ old('first_payment_date') }}" required>
						        </div>
						    </div>


							<div class="col-md-6">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Applied Amount') }}</label>						
							        <input type="text" class="form-control float-field" name="applied_amount" value="{{ old('applied_amount') }}" required>
						        </div>
						    </div>


							<div class="col-md-12">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Attachment') }}</label>						
							        <input type="file" class="dropify" name="attachment" value="{{ old('attachment') }}">
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
								    <label class="control-label">{{ _lang('Remarks') }}</label>						
								    <textarea class="form-control" name="remarks">{{ old('remarks') }}</textarea>
							    </div>
							</div>

								
							<div class="col-md-12">
								<div class="form-group">
									<button type="submit" class="btn btn-primary">{{ _lang('Apply Loan') }}</button>
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
