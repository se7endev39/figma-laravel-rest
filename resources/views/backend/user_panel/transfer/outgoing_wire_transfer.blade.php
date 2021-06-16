@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="arrow-right-circle"></i></div>
				<span>{{ _lang('Outgoing Wire Transfer') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">

			@if(Session::has('wire_success'))
				<div class="alert alert-success">
				   <button type="button" class="close" data-dismiss="alert">&times;</button>
	               <strong>{{ session('wire_success') }}</strong>
				</div>	
			@endif

			<div class="card">
				<div class="card-body">
					<form method="post" class="validate" autocomplete="off" action="{{ url('user/outgoing_wire_transfer') }}">
						{{ csrf_field() }}
						<div class="row p-3">
							<div class="col-md-4 border p-3">
								<h6 class="card-title text-blue">{{ _lang('TRANSFER DETAILS') }}</h6>

								<div class="form-group">
									<label class="control-label">{{ _lang('Debit Account') }}</label>						
									<select class="form-control select2" name="debit_account" required>
										@foreach(\App\Account::where('user_id',Auth::id())->get() as $debit_account )
											<option value="{{ $debit_account->id }}">{{ $debit_account->account_number.' - '.$debit_account->account_type->account_type.' ('.$debit_account->account_type->currency->name.')' }}</option>
										@endforeach
									</select>
								</div>

								<div class="form-group">
									<label class="control-label">{{ _lang('Amount to Transfer') }}</label>						
									<input type="text" class="form-control float-field" name="amount" value="{{ old('amount') }}" required>
								</div>

								<div class="form-group">
									<label class="control-label">{{ _lang('Currency') }}</label>						
									<select class="form-control select2" name="currency" required>
						                 <option value="">{{ _lang('Select Currency') }}</option>
						                 {{ create_option('currency', 'name', 'name','', array('status = ' => 1)) }}
								    </select>		
								</div>

								<div class="form-group">
									<label class="control-label">{{ _lang('Note') }}</label>						
									<textarea class="form-control" name="note">{{ old('note') }}</textarea>
								</div>
							</div>


                   			<!--SPECIFY BENEFICIARY BANK-->
                            <div class="col-md-4 border p-3">
								<h6 class="card-title text-blue">{{ _lang('SPECIFY BENEFICIARY BANK') }}</h6>
								
								<div class="form-group">
									<label class="control-label">{{ _lang('SWIFT / BIC') }}</label>						
									<input type="text" class="form-control" name="swift" value="{{ old('swift') }}" required>
								</div>

								<div class="form-group">
									<label class="control-label">{{ _lang('Bank Name') }}</label>						
									<input type="text" class="form-control" name="bank_name" value="{{ old('bank_name') }}" required>
								</div>

								<div class="form-group">
									<label class="control-label">{{ _lang('Address') }}</label>						
									<textarea class="form-control" name="bank_address">{{ old('bank_address') }}</textarea>
								</div>

								<div class="form-group">
									<label class="control-label">{{ _lang('Country') }}</label>						
									<select class="form-control" name="bank_country" required>
										{{ get_country_list( old('bank_country') ) }}
									</select>	
								</div>

								<div class="form-group">
									<label class="control-label">{{ _lang('ABA/RTN') }}</label>						
									<input type="text" class="form-control" name="rtn" value="{{ old('rtn') }}">
								</div>

                             </div>

                            <!--SPECIFY BENEFICIARY CUSTOMER-->
							<div class="col-md-4 border p-3">
								<h6 class="card-title text-blue">{{ _lang('SPECIFY BENEFICIARY CUSTOMER') }}</h6>

								<div class="form-group">
									<label class="control-label">{{ _lang('Customer Name') }}</label>						
									<input type="text" class="form-control" name="customer_name" value="{{ old('customer_name') }}" required>
								</div>

								<div class="form-group">
									<label class="control-label">{{ _lang('Customer Address') }}</label>						
									<textarea class="form-control" name="customer_address">{{ old('customer_address') }}</textarea>
								</div>

								<div class="form-group">
									<label class="control-label">{{ _lang('Acc#/IBAN') }}</label>						
									<input type="text" class="form-control" name="customer_iban" value="{{ old('customer_iban') }}" required>
								</div>

								<div class="form-group">
									<label class="control-label">{{ _lang('Ref Message') }}</label>						
									<textarea class="form-control" name="reference_message" required>{{ old('reference_message') }}</textarea>
								</div>
							</div>
							 <!--END SPECIFY BENEFICIARY CUSTOMER-->

							
							<div class="form-group">
								<button type="submit" class="btn btn-primary mt-4">{{ _lang('Make Transfer Request') }}</button>
							</div>
							
						</div>			
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

