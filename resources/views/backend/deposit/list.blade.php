@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="pie-chart"></i></div>
				<span>{{ _lang('Deposit History') }}</span>
			</h1>
		</div>
	</div>
</div>


<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card no-export">
				<div class="card-body">
					<h4 class="card-title"><span class="panel-title">{{ _lang('Deposit History') }}</span>
						<button class="btn btn-primary btn-sm float-right ajax-modal" data-title="{{ _lang('Make Deposit') }}" data-href="{{ route('deposit.create') }}">{{ _lang('Add Deposit') }}</button>
					</h4>
					<table class="table table-bordered data-table">
						<thead>
							<tr>
								<th>{{ _lang('Date') }}</th>
								<th>{{ _lang('Account') }}</th>
								<th>{{ _lang('Deposit Method') }}</th>
								<th>{{ _lang('Type') }}</th>
								<th class="text-right">{{ _lang('Amount') }}</th>
								<th>{{ _lang('Status') }}</th>
								<th>{{ _lang('User') }}</th>
								<th class="text-center">{{ _lang('Action') }}</th>
							</tr>
						</thead>
						<tbody>

							@foreach($deposits as $deposit)
							<tr id="row_{{ $deposit->id }}">
								<td class='created_at'>{{ $deposit->created_at }}</td>
								<td class='account_id'>{{ $deposit->account->account_number.' ('.$deposit->account->account_type->currency->name.')' }}</td>
								<td class='method'>{{ $deposit->method }}</td>
								<td class='type'>{{ ucwords(str_replace('_',' ',$deposit->type)) }}</td>
								<td class='amount text-right'>{{ $deposit->amount }}</td>
								<td class='status'>
									@if($deposit->status == 0)
									<span class="badge badge-warning">{{ _lang('Pending') }}</span>
									@elseif($deposit->status == 1)
									<span class="badge badge-success">{{ _lang('Completed') }}</span>
									@elseif($deposit->status == 2)
									<span class="badge badge-danger">{{ _lang('Canceled') }}</span>
									@endif
								</td>
								<td class='user_id'>{{ isset($deposit->user) ? $deposit->user->first_name.' '.$deposit->user->last_name : '' }}</td>

								<td class="text-center">
									<div class="dropdown">
										<button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											{{ _lang('Action') }}
										</button>
										<form action="{{ action('DepositController@destroy', $deposit['id']) }}" method="post">
											{{ csrf_field() }}
											<input name="_method" type="hidden" value="DELETE">

											<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
												<button data-href="{{ action('DepositController@edit', $deposit['id']) }}" data-title="{{ _lang('Update Deposit') }}" class="dropdown-item dropdown-edit ajax-modal"><i class="mdi mdi-pencil"></i> {{ _lang('Edit') }}</button>
												<button data-href="{{ action('DepositController@show', $deposit['id']) }}" data-title="{{ _lang('View Deposit') }}" class="dropdown-item dropdown-view ajax-modal"><i class="mdi mdi-eye"></i> {{ _lang('View') }}</button>
												<button class="btn-remove dropdown-item" type="submit"><i class="mdi mdi-delete"></i> {{ _lang('Delete') }}</button>
											</div>
										</form>
									</div>

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


