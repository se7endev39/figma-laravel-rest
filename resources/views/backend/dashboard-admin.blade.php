@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="activity"></i></div>
				<span>{{ _lang('Dashboard') }}</span>
			</h1>
            
            <div class="float-right">
	            <label class="select_currency">{{ _lang('Select Currency') }}</label>
				<select name="currency" class="currency_chooser" id="currency_chooser">
					{{ create_option('currency', 'name', 'name', isset($_GET['currency']) ? $_GET['currency'] : $currency , array('status =' => 1)) }}
				</select>
			</div>
		</div>
	</div>
</div>


<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-md-6">
			<div class="card mb-4">
				<div class="card-header">{{ _lang('Yearly Deposit').' - '.date('Y') }}</div>
				<div class="card-body">
					<div class="chart-area"><canvas id="yearlyDeposit" width="100%" height="30"></canvas></div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card mb-4">
				<div class="card-header">{{ _lang('Yearly Withdraw').' - '.date('Y') }}</div>
				<div class="card-body">
					<div class="chart-bar"><canvas id="yearlyWithdraw" width="100%" height="30"></canvas></div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xl-3 col-md-6">
			<div class="card bg-primary text-white mb-4">
				<div class="card-body">
					<p>{{ _lang('Verified Users') }}</p>
					<h5 class="mt-2">{{ $verified_user_count }}</h5>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6">
			<div class="card bg-warning text-white mb-4">
				<div class="card-body">
					<p>{{ _lang('Pending Loan') }}</p>
					<h5 class="mt-2">{{ $pending_loan_count }}</h5>
				</div>
				
			</div>
		</div>
		<div class="col-xl-3 col-md-6">
			<div class="card bg-success text-white mb-4">
				<div class="card-body">
					<p>{{ _lang('Total Deposit') }}</p>
					<h5 class="mt-2">{{ $currency.' '.decimalPlace($total_deposit) }}</h5>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6">
			<div class="card bg-danger text-white mb-4">
				<div class="card-body">
					<p>{{ _lang('Total Withdraw') }}</p>
					<h5 class="mt-2">{{ $currency.' '.decimalPlace($total_withdraw) }}</h5>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
			    <div class="card-header">{{ _lang('Recent 20 Transactions') }}</div>
				<div class="card-body">
				    <table class="table table-striped data-table">
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
							@foreach($recent_transactions as $transaction)
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
$(document).on('change','#currency_chooser',function(){
	window.location.href = "{{ url('dashboard') }}?currency=" + $(this).val();
})
</script>
@endsection