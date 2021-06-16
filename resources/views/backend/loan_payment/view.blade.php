@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="eye"></i></div>
				<span>{{ _lang('View Payment Details') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
			    <div class="card-header">
					<span class="panel-title">{{ _lang('View Payment Details') }}</span>
				</div>
				
				<div class="card-body">
				    <table class="table table-bordered">
					    <tr><td>{{ _lang('Loan ID') }}</td><td>{{ $loanpayment->loan->loan_id }}</td></tr>
						<tr><td>{{ _lang('Payment Date') }}</td><td>{{ date('d/M/Y',strtotime($loanpayment->paid_at)) }}</td></tr>
						<tr><td>{{ _lang('Late Penalties') }}</td><td>{{ $loanpayment->late_penalties }}</td></tr>
						<tr><td>{{ _lang('Interest') }}</td><td>{{ $loanpayment->interest }}</td></tr>
						<tr><td>{{ _lang('Amount To Pay') }}</td><td>{{ $loanpayment->amount_to_pay }}</td></tr>
						<tr><td>{{ _lang('Remarks') }}</td><td>{{ $loanpayment->remarks }}</td></tr>
				    </table>
				</div>
		    </div>
		</div>
	</div>
</div>
@endsection


