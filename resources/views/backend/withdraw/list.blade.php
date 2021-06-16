@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="pie-chart"></i></div>
				<span>{{ _lang('Withdraw History') }}</span>
			</h1>
		</div>
	</div>
</div>


<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card no-export">
				<div class="card-body">
					<h4 class="card-title"><span class="panel-title">{{ _lang('Withdraw History') }}</span>
						<button class="btn btn-primary btn-sm float-right ajax-modal" data-title="{{ _lang('Make Withdraw') }}" data-href="{{route('withdraw.create')}}">{{ _lang('Add New') }}</button>
					</h4>
					<table class="table table-bordered data-table">
						<thead>
							<tr>
								<th>{{ _lang('Date') }}</th>
								<th>{{ _lang('Account') }}</th>
								<th>{{ _lang('Withdraw Method') }}</th>
								<th>{{ _lang('Amount') }}</th>
								<th>{{ _lang('Status') }}</th>
								<th>{{ _lang('User') }}</th>
								<th class="text-center">{{ _lang('Action') }}</th>
							</tr>
						</thead>
						<tbody>
							
							@foreach($withdraws as $withdraw)
							<tr id="row_{{ $withdraw->id }}">
								<td class='created_at'>{{ $withdraw->created_at }}</td>
								<td class='account_id'>{{ $withdraw->account->account_number.' ('.$withdraw->account->account_type->currency->name.')' }}</td>
								<td class='method'>{{ $withdraw->method }}</td>
								<td class='amount'>{{ $withdraw->amount }}</td>
								<td class='status'>
									@if($withdraw->status == 0)
									<span class="badge badge-warning">{{ _lang('Pending') }}</span>
									@elseif($withdraw->status == 1)
									<span class="badge badge-success">{{ _lang('Completed') }}</span>
									@elseif($withdraw->status == 2)
									<span class="badge badge-danger">{{ _lang('Canceled') }}</span>
									@endif
								</td>
								<td class='user_id'>{{ $withdraw->user->first_name.' '.$withdraw->user->last_name }}</td>

								
								<td class="text-center">
									<div class="dropdown">
										<button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											{{ _lang('Action') }}
										</button>
										<form action="{{ action('WithdrawController@destroy', $withdraw['id']) }}" method="post">
											{{ csrf_field() }}
											<input name="_method" type="hidden" value="DELETE">
											
											<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
												<button data-href="{{ action('WithdrawController@edit', $withdraw['id']) }}" data-title="{{ _lang('Update Withdraw') }}" class="dropdown-item dropdown-edit ajax-modal"><i class="mdi mdi-pencil"></i> {{ _lang('Edit') }}</button>
												<button data-href="{{ action('WithdrawController@show', $withdraw['id']) }}" data-title="{{ _lang('View Withdraw') }}" class="dropdown-item dropdown-view ajax-modal"><i class="mdi mdi-eye"></i> {{ _lang('View') }}</button>
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


