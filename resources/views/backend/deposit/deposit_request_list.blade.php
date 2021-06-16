@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="bar-chart"></i></div>
				<span>{{ _lang('Deposit Requests') }}</span>
			</h1>
		</div>
	</div>
</div>


<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card no-export">
			
				<div class="card-header d-flex justify-content-between align-items-center">
					<span class="panel-title">{{ _lang('Deposit Requests Via Wire Transfer') }}</span>	
				</div>
				
				<div class="card-body">

					<table class="table table-bordered data-table">
						<thead>
							<tr>
								<th>{{ _lang('Date') }}</th>
								<th>{{ _lang('Transaction ID') }}</th>
								<th>{{ _lang('User') }}</th>
								<th>{{ _lang('Account') }}</th>
								<th class="text-right">{{ _lang('Amount') }}</th>
								<th>{{ _lang('Status') }}</th>			
								<th class="text-center">{{ _lang('Action') }}</th>	
							</tr>
						</thead>
						<tbody>

							@foreach($deposit_requests as $deposit)
							<tr id="row_{{ $deposit->id }}">
								<td class='created_at'>{{ $deposit->created_at }}</td>
								<td class='transaction_id'><b>{{ $deposit->transaction_id }}</b></td>
								<td class='user_id'>{{ isset($deposit->user) ? $deposit->user->first_name.' '.$deposit->user->last_name : '' }}</td>
								<td class='account_id'>{{ $deposit->account->account_number }}</td>
								<td class='amount text-right'>{{ $deposit->account->account_type->currency->name.' '.decimalPlace($deposit->amount + $deposit->charge) }}</td>
								<td class='status'>
								    @if($deposit->status == 'pending')
										<span class="badge badge-warning">{{ ucwords($deposit->status) }}</span>
								    @elseif($deposit->status == 'reject')
										<span class="badge badge-danger">{{ ucwords($deposit->status) }}</span>
									@elseif($deposit->status == 'approve')
										<span class="badge badge-success">{{ ucwords($deposit->status) }}</span>
									@endif
								</td>
								@if($deposit->status == 'pending')
									<td class="text-center">
										<div class="dropdown">
											<button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												{{ _lang('Action') }}
											</button>
						
											<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
												<a href="{{ url('admin/deposit/request/'.$deposit->status .'/'. $deposit['id'] .'/approve') }}" class="dropdown-item"><i class="mdi mdi-pencil"></i> {{ _lang('Approve') }}</a>
												<a href="{{ url('admin/deposit/request/'.$deposit->status .'/'. $deposit['id'] .'/reject') }}" class="dropdown-item"><i class="mdi mdi-eye"></i> {{ _lang('Reject') }}</a>
											</div>	
										</div>
									</td>
								@else
									<td class="text-center">{{ _lang('No Action') }}</td>	
								@endif
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


