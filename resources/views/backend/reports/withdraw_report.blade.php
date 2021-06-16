@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="bar-chart"></i></div>
				<span>{{ _lang('Withdraw Report') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card panel-default">

				<span class="d-none panel-title">{{ _lang('Withdraw Report') }}</span>

				<div class="card-body">

					<div class="report-params">
						<form class="validate" method="post" action="{{ url('admin/reports/withdraw_report/view') }}">
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

					<div class="report-header mt-2">
						<h5>{{  _lang('Withdraw Report') }}</h5>
						<h6>{{ isset($date1) ? date('d M, Y',strtotime($date1)).' '._lang('to').' '.date('d M, Y',strtotime($date2)) : '-------------  '._lang('to').'  -------------' }}</h6>
					</div>

					<table class="table table-striped report-table">
						<thead>
							<tr>
								<th>{{ _lang('Date') }}</th>
								<th>{{ _lang('Account') }}</th>
								<th>{{ _lang('Withdraw Method') }}</th>
								<th class="text-right">{{ _lang('Amount') }}</th>
								<th>{{ _lang('Status') }}</th>
								<th>{{ _lang('User') }}</th>
								<th class="text-center">{{ _lang('Details') }}</th>
							</tr>
						</thead>
						<tbody>
							@if( isset($report_data) )
								@foreach($report_data as $withdraw)
									<tr id="row_{{ $withdraw->id }}">
										<td class='created_at'>{{ $withdraw->created_at }}</td>
										<td class='account_id'>{{ $withdraw->account->account_number.' ('.$withdraw->account->account_type->currency->name.')' }}</td>
										<td class='method'>{{ $withdraw->method }}</td>
										<td class='amount text-right'>{{ $withdraw->amount }}</td>
										<td class='status'>
											@if($withdraw->status == 0)
											<span class="badge badge-warning">{{ _lang('Pending') }}</span>
											@elseif($withdraw->status == 1)
											<span class="badge badge-success">{{ _lang('Completed') }}</span>
											@elseif($withdraw->status == 2)
											<span class="badge badge-danger">{{ _lang('Canceled') }}</span>
											@endif
										</td>
										<td class='user_id'>{{ isset($withdraw->user) ? $withdraw->user->first_name.' '.$withdraw->user->last_name : '' }}</td>

										<td class="text-center">
											<button data-href="{{ action('WithdrawController@show', $withdraw['id']) }}" data-title="{{ _lang('View Withdraw') }}" class="btn btn-primary btn-sm ajax-modal"><i class="mdi mdi-eye"></i> {{ _lang('View') }}</button>
										</td>
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


