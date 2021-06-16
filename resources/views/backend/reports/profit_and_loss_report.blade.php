@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="bar-chart"></i></div>
				<span>{{ _lang('Profit and Loss Report') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-12">
			<div class="card panel-default">

				<span class="d-none panel-title">{{ _lang('Profit and Loss Report') }}</span>

				<div class="card-body">

					<div class="report-params">
						<form class="validate" method="post" action="{{ url('admin/reports/profit_and_loss_report/view') }}">
							<div class="row">
								{{ csrf_field() }}


								<div class="col-md-3">
									<div class="form-group">
										<label class="control-label">{{ _lang('From') }}</label>						
										<input type="text" class="form-control datepicker" name="date1" id="date1" value="{{ isset($date1) ? $date1 : old('date1') }}" readOnly="true" required>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label class="control-label">{{ _lang('To') }}</label>						
										<input type="text" class="form-control datepicker" name="date2" id="date2" value="{{ isset($date2) ? $date2 : old('date2') }}" readOnly="true" required>
									</div>
								</div>


								<div class="col-md-2">
									<button type="submit" class="btn btn-primary" style="margin-top: 32px;">{{ _lang('View Report') }}</button>
								</div>
							</form>

						</div>
					</div><!--End Report param-->
                    
					<br>
					<button type="button" class="btn btn-primary print" data-print="main-report">{{ _lang('Print Report') }}</button>
					
					<div id="main-report">
						<div class="report-header mt-2">
							<h5>{{ get_option('company_name') }}</h5>
							<h5>{{  _lang('Profit and Loss Report') }}</h5>
							<h6>{{ isset($date1) ? date('d M, Y',strtotime($date1)).' '._lang('to').' '.date('d M, Y',strtotime($date2)) : '-------------  '._lang('to').'  -------------' }}</h6>
						</div>

						@php $currency = get_base_currency(); @endphp
						@php $total_income = 0; @endphp
						@php $total_expense = 0; @endphp
						
						<table class="table table-bordered">
							<tbody>	
								<tr>
									<td><b>{{ _lang('Income') }}</b></td>
									<td class="text-right"><b>{{ _lang('Amount') }}</b></td>
								</tr>

								@if( isset($transaction_fees_amount) )
									<tr>
										<td>{{ _lang('Transactions Fee') }}</td>
										<td class="text-right">{{ $currency.' '.decimalPlace($transaction_fees_amount) }}</td>
									</tr>
									
									<tr>
										<td>{{ _lang('Custom Fee') }}</td>
										<td class="text-right">{{ $currency.' '.decimalPlace($custom_fees_amount) }}</td>
									</tr>

									<tr>
										<td>{{ _lang('Loan Profit') }}</td>
										<td class="text-right">{{ $currency.' '.decimalPlace($loan_profit_amount) }}</td>
									</tr>
									
									@php $total_income += $transaction_fees_amount + $custom_fees_amount + $loan_profit_amount; @endphp
									
									@foreach($other_incomes as $income)
										<tr>
											<td>{{ $income->category->name }}</b></td>
											<td class="text-right">{{ $currency.' '.decimalPlace($income->amount) }}</td>
										</tr>
										@php $total_income += $income->amount; @endphp
									@endforeach
									
									
								@endif
								
								<tr>
									<td><b>{{ _lang('Expense') }}</b></td>
									<td class="text-right"><b>{{ _lang('Amount') }}</b></td>
								</tr>
								
								@if( isset($transaction_fees_amount) )
									<tr>
										<td>{{ _lang('Referral Expense') }}</td>
										<td class="text-right">{{ $currency.' '.decimalPlace($referral_fees_amount) }}</td>
									</tr>
									
									@php $total_expense += $referral_fees_amount; @endphp
									
									@foreach($other_expenses as $expense)
										<tr>
											<td>{{ $expense->category->name }}</td>
											<td class="text-right">{{ $currency.' '.decimalPlace($expense->amount) }}</td>
										</tr>
										@php $total_expense += $expense->amount; @endphp
									@endforeach

								@endif
								<tr>
									<td><b>{{ $total_income >= $total_expense ? _lang('Total Profit') : _lang('Total Loss') }}</b></td>
									<td class="text-right"><b>{{ $total_income >= $total_expense ? $currency.' '.decimalPlace($total_income) : $currency.' '.decimalPlace($total_expense) }}</b></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('js-script')
<script>
	document.title = $(".panel-title").html();
	$("#status").val("{{ isset($status) ? $status : 'all' }}");
</script>
@endsection


