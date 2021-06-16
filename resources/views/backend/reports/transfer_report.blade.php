@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="bar-chart"></i></div>
				<span>{{ _lang('Transactions Report') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card panel-default">

				<span class="d-none panel-title">{{ _lang('Transactions Report') }}</span>

				<div class="card-body">

					<div class="report-params">
						<form class="validate" method="post" action="{{ url('admin/reports/transactions_report/view') }}">
							<div class="row">
								{{ csrf_field() }}

								<div class="col-md-2">
									<div class="form-group">
										<label class="control-label">{{ _lang('From') }}</label>						
										<input type="text" class="form-control datepicker" name="date1" id="date1" value="{{ isset($date1) ? $date1 : old('date1') }}" readOnly="true" required>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label class="control-label">{{ _lang('To') }}</label>						
										<input type="text" class="form-control datepicker" name="date2" id="date2" value="{{ isset($date2) ? $date2 : old('date2') }}" readOnly="true" required>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label class="control-label">{{ _lang('Transaction Type') }}</label>					
										<select class="form-control select2" name="type" id="type" required>
											<option value="all">{{ _lang('All') }}</option>
											<option value="deposit">{{ _lang('Deposit') }}</option>
											<option value="withdraw">{{ _lang('Withdraw') }}</option>							
											<option value="transfer">{{ _lang('Transfer') }}</option>		
											<option value="fee">{{ _lang('Fee') }}</option>		
											<option value="payment">{{ _lang('Payment') }}</option>	
											<option value="revenue">{{ _lang('Revenue') }}</option>												
											<option value="wire_transfer">{{ _lang('Wire Transfer') }}</option>					
										</select> 
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label class="control-label">{{ _lang('Status') }}</label>						
										<select class="form-control select2" name="status" id="status" required>
											<option value="all">{{ _lang('All') }}</option>
											<option value="complete">{{ _lang('Completed') }}</option>
											<option value="pending">{{ _lang('Pending') }}</option>								
											<option value="reject">{{ _lang('Rejected') }}</option>							
										</select> 
									</div>
								</div>


								<div class="col-md-2">
									<button type="submit" class="btn btn-primary" style="margin-top: 34px;">{{ _lang('View Report') }}</button>
								</div>
							</form>

						</div>
					</div><!--End Report param-->

					<div class="report-header mt-2">
						<h5>{{ isset($account) ? _lang('Transactions Report of').' '.$acc->account_number.' - '.$acc->account_type->account_type : _lang('Transactions Report') }}</h5>
						<h6>{{ isset($date1) ? date('d M, Y',strtotime($date1)).' '._lang('to').' '.date('d M, Y',strtotime($date2)) : '-------------  '._lang('to').'  -------------' }}</h6>
					</div>

					<table class="table table-striped report-table">
						<thead>
							<th>{{ _lang('Date') }}</th>
							<th>{{ _lang('Account') }}</th>
							<th>{{ _lang('DR/CR') }}</th>
							<th class="text-right">{{ _lang('Amount') }}</th>
							<th>{{ _lang('Type') }}</th>
							<th>{{ _lang('Status') }}</th>
							<th>{{ _lang('Details') }}</th>
						</thead>
						<tbody>
							@if( isset($report_data) )
								@foreach($report_data as $transaction)
								<tr>
									<td>{{ $transaction->created_at }}</td>
									<td>{{ $transaction->account->account_number }}</td>
									<td>
									    @if($transaction->dr_cr == 'dr')
											<span class="badge badge-danger">{{ _lang('Debit') }}</span>
										@elseif($transaction->dr_cr == 'cr')
											<span class="badge badge-success">{{ _lang('Credit') }}</span>
										@endif
									</td>
									<td class="text-right {{ $transaction->dr_cr == 'cr' ? 'text-green' : 'text-red' }} {{ $transaction->status == 'reject' ? 'text-rejected' : '' }}"><b>{{ $transaction->account->account_type->currency->name.' '.decimalPlace($transaction->amount) }}</b></td>
	                                <td>{{ ucwords(str_replace('_',' ',$transaction->type)) }}</td>
									<td class="status">
									   @if($transaction->status == 'pending')
											<span class="badge badge-warning">{{ _lang('Pending') }}</span>
										@elseif($transaction->status == 'complete')
											<span class="badge badge-success">{{ _lang('Completed') }}</span>
										@elseif($transaction->status == 'reject')
											<span class="badge badge-danger">{{ _lang('Rejected') }}</span>
										@endif
									</td>

									<td class="text-center"><button class="btn btn-primary btn-sm ajax-modal" data-title="{{ _lang('View Transaction Details') }}" data-href="{{ url('admin/transfer_request/'. $transaction->status . '/' . $transaction->id) }}">{{ _lang('View') }}</button></td>
								</tr>
								@endforeach
							@endif
						</tbody>
					</table>
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


