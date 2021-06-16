@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="stop-circle"></i></div>
				<span>{{ _lang('My Loans') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
					<span class="panel-title">{{ _lang('My Loans') }}</span>
					<a class="btn btn-primary btn-sm float-right" href="{{ url('user/loans/apply_loan') }}">{{ _lang('Apply New Loan') }}</a>
				</div>
				
				<div class="card-body">
					 <table class="table table-bordered data-table">
						<thead>
						  	<tr>
								<th>{{ _lang('Loan ID') }}</th>
								<th>{{ _lang('Loan Product') }}</th>
								<th>{{ _lang('Account') }}</th>
								<th class="text-right">{{ _lang('Applied Amount') }}</th>
								<th class="text-right">{{ _lang('Total Payable') }}</th>
								<th class="text-right">{{ _lang('Amount Paid') }}</th>
								<th class="text-right">{{ _lang('Due Amount') }}</th>
								<th>{{ _lang('Release Date') }}</th>
								<th>{{ _lang('Status') }}</th>
						  	</tr>
						</thead>
						<tbody>  
						  @foreach($loans as $loan)
						  	<tr>
								<td><a href="{{ url('user/loans/'.$loan->id) }}">{{ $loan->loan_id }}</a></td>
								<td>{{ $loan->loan_product->name }}</td>
								<td>{{ $loan->account->account_number }}</td>
								<td class="text-right">{{ decimalPlace($loan->applied_amount) }}</td>
								<td class="text-right">{{ decimalPlace($loan->total_payable) }}</td>
								<td class="text-right">{{ decimalPlace($loan->total_paid) }}</td>
								<td class="text-right">{{ decimalPlace($loan->total_payable - $loan->total_paid) }}</td>
								<td>{{ date('d/M/Y',strtotime($loan->release_date)) }}</td>
								<td>
									@if($loan->status == 0) 
										{!! status(_lang('Pending'), 'warning') !!} 
									@elseif($loan->status == 1)	
										{!! status(_lang('Approved'), 'success') !!}
									@elseif($loan->status == 2)
										{!! status(_lang('Completed'), 'info') !!}
									@endif	
								</td>
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
