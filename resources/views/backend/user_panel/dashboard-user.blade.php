@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="activity"></i></div>
				<span>{{ _lang('Account Overview') }}</span>
			</h1>
			<p class="text-white mt-1">{{ _lang('Last Login') .': '. date('M d, Y h:m A',strtotime(auth()->user()->last_login_at)) }}</p>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">

		<div class="col-md-12">
			<div class="card mb-4">
				<div class="card-body">
					 <ul class="nav nav-pills" role="tablist">
					    <li class="nav-item">
					      <a class="nav-link active" data-toggle="pill" href="#accounts">{{ _lang('ACCOUNT OVERVIEW') }}</a>
					    </li>
					    <li class="nav-item">
					      <a class="nav-link" data-toggle="pill" href="#cards">{{ _lang('MY CARDS') }}</a>
					    </li>
					  </ul>

					<div class="tab-content">
						<div id="accounts" class="tab-pane active"><br>
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<th>{{ _lang('Account') }}</th>
										<th class="text-right">{{ _lang('Balance') }}</th>
										<th>{{ _lang('Status') }}</th>
										<th class="text-center">{{ _lang('Details') }}</th>
									</thead>
									<tbody>
										@foreach($accounts as $account)
										<tr>
											<td>{{ $account->account_number.' - '.$account->account_type->account_type }}</td>
											<td class="text-right"><b>{{ $account->account_type->currency->name.' '.decimalPlace($account->balance) }}</b></td>
											<td>{{ $account->status == 1 ? _lang('Active') : _lang('Blocked') }}</td>
											<td class="text-center"><button class="btn btn-primary btn-sm ajax-modal" data-title="{{ _lang('View Account Details') }}" data-href="{{ url('user/accounts/'.$account->id) }}">{{ _lang('View') }}</button></td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>

						<div id="cards" class="tab-pane"><br>
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<th>{{ _lang('Card Number') }}</th>
										<th>{{ _lang('CVV') }}</th>
										<th>{{ _lang('Status') }}</th>
										<th class="text-right">{{ _lang('Balance') }}</th>
									</thead>
									<tbody>
										@foreach($cards as $card)
										<tr>
											<td>{{ $card->card_number.' - '.$card->card_type->card_type }}</td>
											<td>{{ $card->cvv }}</td>
											<td>{{ $card->status == 1 ? _lang('Active') : _lang('Blocked') }}</td>
											<td class="text-right"><b>{{ $card->card_type->currency->name.' '.decimalPlace($card->balance) }}</b></td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div><!--End Cards-->	
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="card mb-4">
				<div class="card-header">{{ _lang('Upcoming Loan Payments') }}</div>

				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered">
							<thead>
								<th>{{ _lang('Loan ID') }}</th>
								<th>{{ _lang('Next Payment Date') }}</th>
								<th>{{ _lang('Status') }}</th>
								<th class="text-right">{{ _lang('Amount to Pay') }}</th>
								<th class="text-center">{{ _lang('Action') }}</th>
							</thead>
							<tbody>
								@if(count($loans) == 0)
									<tr>
										<td colspan="5"><h6 class="text-center">{{ _lang('No Active Loan Available') }}</h6></td>
									</tr>
								@endif

								@foreach($loans as $loan)
								<tr>
									<td>{{ $loan->loan_id }}</td>
									<td>{{ date('d/M/Y',strtotime($loan->next_payment->repayment_date)) }}</td>
									<td>{!! $loan->next_payment->repayment_date >= date('Y-m-d') ? status(_lang('Upcoming'),'success') : status(_lang('Due'),'danger') !!}</td>
									<td class="text-right">{{ $loan->account->account_type->currency->name.' '.$loan->next_payment->amount_to_pay }}</td>
									<td class="text-center"><a href="{{ url('user/loans/payment/'.$loan->id) }}" class="btn btn-primary btn-sm">{{ _lang('Pay Now') }}</a></td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="card mb-4">
				<div class="card-header">{{ _lang('Recent Transactions') }}</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-striped" id="recent_transactions">
							<thead>
								<th>{{ _lang('Date') }}</th>
								<th>{{ _lang('Account') }}</th>
								<th>{{ _lang('DR/CR') }}</th>
								<th class="text-right">{{ _lang('Amount') }}</th>
								<th>{{ _lang('Type') }}</th>
								<th>{{ _lang('Status') }}</th>
								<th class="text-center">{{ _lang('Details') }}</th>
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

									<td class="text-center"><a class="btn btn-primary btn-sm ajax-modal" data-title="{{ _lang('View Transaction Details') }}" href="{{ url('user/view_transaction/'.$transaction->id) }}">{{ _lang('View') }}</a></td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>                        
@endsection
