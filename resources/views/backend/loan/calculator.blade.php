@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="plus-circle"></i></div>
				<span>{{ _lang('Loan Calculator') }}</span>
			</h1>
		</div>
	</div>
</div>


<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header">
					<span class="panel-title">{{ _lang('Loan Calculator') }}</span>
				</div>
				<div class="card-body">
				    <form method="post" class="validate" autocomplete="off" action="{{ route('loans.calculate') }}">
						{{ csrf_field() }}
						<div class="row">
							
							<div class="col-md-3">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Apply Amount') }}</label>						
							        <input type="text" class="form-control float-field" name="apply_amount" value="{{ old('apply_amount',$apply_amount) }}" required>
						        </div>
						    </div>

							<div class="col-md-3">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Interest Rate Per Year') }}</label>
							        <div class="input-group">
								        <input type="text" class="form-control float-field" name="interest_rate" value="{{ old('interest_rate', $interest_rate) }}" required>
								        <div class="input-group-append">
										    <span class="input-group-text">%</span>
										</div>
									</div>
						        </div>
						    </div>

							<div class="col-md-3">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Interest Type') }}</label>						
							        <select class="form-control auto-select" data-selected="{{ old('interest_type',$interest_type) }}" name="interest_type" id="interest_type" required>
										<option value="">{{ _lang('Select One') }}</option>
										<option value="flat_rate">{{ _lang('Flat Rate') }}</option>
										<option value="fixed_rate">{{ _lang('Fixed Rate') }}</option>
										<option value="mortgage">{{ _lang('Mortgage amortization') }}</option>
										<option value="one_time">{{ _lang('One-time payment') }}</option>
									</select>
								</div>
						    </div>

							<div class="col-md-3">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Term') }}</label>						
							        <input type="number" class="form-control" name="term" value="{{ old('term',$term) }}" id="term" required>
						        </div>
						    </div>

							<div class="col-md-3">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Term Period') }}</label>						
							        <select class="form-control auto-select" data-selected="{{ old('term_period', $term_period) }}" name="term_period" id="term_period" required>
							        	<option value="">{{ _lang('Select One') }}</option>
										<option value="+1 day">{{ _lang('Day') }}</option>
										<option value="+1 week">{{ _lang('Week') }}</option>
										<option value="+1 month">{{ _lang('Month') }}</option>
										<option value="+1 year">{{ _lang('Year') }}</option>
									</select>
								</div>
						    </div>

						    <div class="col-md-3">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('First Payment date') }}</label>
							        <input type="text" class="form-control datepicker" name="first_payment_date" value="{{ old('first_payment_date', $first_payment_date) }}" required>
						        </div>
						    </div>

						    <div class="col-md-3">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Late Payment Penalties') }}</label>		
							        <div class="input-group">
								        <input type="text" class="form-control float-field" name="late_payment_penalties" value="{{ old('late_payment_penalties',$late_payment_penalties) }}" required>
								        <div class="input-group-append">
										    <span class="input-group-text">%</span>
										</div>
									</div>
						        </div>
						    </div>
								
							<div class="col-md-3">
								<div class="form-group">
									<button type="submit" class="btn btn-primary btn-block" style="margin-top: 33px;">{{ _lang('Calculate') }}</button>
								</div>
							</div>
						</div>			
				    </form>

				    @if(isset($table_data))

				        <h5 class="mt-4 text-center">{{ _lang('Payable Amount') }}: {{ decimalPlace($payable_amount) }}</h5>

						<div class="table-responsive mt-5">
							<table class="table table-bordered">
							    <thead>
							        <tr>
							            <th>{{ _lang('Date') }}</th>
							            <th class="text-right">{{ _lang('Amount to Pay') }}</th>
							            <th class="text-right">{{ _lang('Penalty') }}</th>
							            <th class="text-right">{{ _lang('Principal Amount') }}</th>
							            <th class="text-right">{{ _lang('Interest') }}</th>
							            <th class="text-right">{{ _lang('Balance') }}</th>
							        </tr>
							    </thead>    
							    <tbody>
							    	@foreach($table_data as $td)
						            <tr>
						                <td>{{ date('d/m/Y',strtotime($td['date'])) }}</td>
						                <td class="text-right">{{ decimalPlace($td['amount_to_pay']) }}</td>
						                <td class="text-right">{{ decimalPlace($td['penalty']) }}</td>
						                <td class="text-right">{{ decimalPlace($td['principle_amount']) }}</td>
						                <td class="text-right">{{ decimalPlace($td['interest']) }}</td>
						                <td class="text-right">{{ decimalPlace($td['balance']) }}</td>
						            </tr>
						            @endforeach
							    </tbody>
							</table>
						</div>
					@endif

				</div>
			</div>
	    </div>
	</div>


</div>
@endsection


@section('js-script')
<script>
$(document).on('change','#interest_type',function(){
   if($(this).val() == 'one_time'){
   		$("#term").val(1);
   		$("#term").prop('readonly',true);
   		$("#term_period").prop('disabled',true);
   }else{
   		$("#term").prop('readonly',false);
   		$("#term_period").prop('disabled',false);
   }

});
</script>
@endsection