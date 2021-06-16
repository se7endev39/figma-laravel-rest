@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="eye"></i></div>
				<span>{{ _lang('View Withdraw Details') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title panel-title">{{ _lang('View Withdraw') }}</h4>

					<table class="table table-bordered">
						<tr><td>{{ _lang('Created') }}</td><td>{{ $withdraw->created_at }}</td></tr>
						<tr><td>{{ _lang('Account') }}</td><td>{{ $withdraw->account->account_number.' ('.$withdraw->account->account_type->currency->name.')' }}</td></tr>
						<tr><td>{{ _lang('Deposit Method') }}</td><td>{{ $withdraw->method }}</td></tr>
						<tr><td>{{ _lang('Amount') }}</td><td>{{ $withdraw->amount }}</td></tr>
						<tr><td>{{ _lang('Note') }}</td><td>{{ $withdraw->note }}</td></tr>
						<tr>
							<td>{{ _lang('Status') }}</td>
							<td>
								@if($withdraw->status == 0)
								<span class="badge badge-warning">{{ _lang('Pending') }}</span>
								@elseif($withdraw->status == 1)
								<span class="badge badge-success">{{ _lang('Completed') }}</span>
								@elseif($withdraw->status == 2)
								<span class="badge badge-danger">{{ _lang('Canceled') }}</span>
								@endif
							</td>
						</tr>
						<tr><td>{{ _lang('User') }}</td><td>{{ $withdraw->user->first_name.' '.$withdraw->user->last_name }}</td></tr>
						<tr><td>{{ _lang('User Email') }}</td><td>{{ $withdraw->user->email }}</td></tr>
						@if(Auth::user()->user_type == 'admin')
							<tr><td>{{ _lang('Created By') }}</td><td>{{ $withdraw->transaction->created_user->first_name.' ('.$withdraw->transaction->created_at.')' }}</td></tr>
							<tr><td>{{ _lang('Updated By') }}</td><td>{{ $withdraw->transaction->updated_user->first_name.' ('.$withdraw->transaction->updated_at.')' }}</td></tr>
						@endif
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection


