@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="bar-chart"></i></div>
				<span>{{ _lang('Profit By User') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card panel-default">

				<span class="d-none panel-title">{{ _lang('Profit By User') }}</span>

				<div class="card-body">

					<div class="report-params">
						<form class="validate" method="post" action="{{ url('admin/reports/profit_report_by_user/view') }}">
							<div class="row">
								{{ csrf_field() }}

								<div class="col-md-3">
									<div class="form-group">
										<label class="control-label">{{ _lang('Select User') }}</label>						
										<select class="form-control select2" name="user_id" id="user_id" required>
											<option value="">{{ _lang('Select User') }}</option>
											@foreach(\App\User::where('user_type','user')->get() as $user )
												<option value="{{ $user->id }}">{{ $user->first_name.' - '.$user->last_name.' ('.$user->email.')' }}</option>
											@endforeach
										</select>
									</div>
								</div>
								
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
									<button type="submit" class="btn btn-primary" style="margin-top: 34px;">{{ _lang('View Report') }}</button>
								</div>
							</form>

						</div>
					</div><!--End Report param-->

					<div class="report-header mt-2">
						<h5>{{  _lang('Profit By User') }}</h5>
						<h6>{{ isset($date1) ? date('d M, Y',strtotime($date1)).' '._lang('to').' '.date('d M, Y',strtotime($date2)) : '-------------  '._lang('to').'  -------------' }}</h6>
					</div>

					<table class="table table-striped report-table">
						 <thead>
						    <th>{{ _lang('Currency') }}</th>
						    <th>{{ _lang('Amount') }}</th>
						</thead>
						<tbody>  
						  @if(isset($report_data))						
							  @foreach($report_data as $bank_profit)
								<tr>
								   <td>{{ $bank_profit->currency }}</td>
								   <td>{{ $bank_profit->profit }}</td>
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
	$("#user_id").val("{{ isset($user_id) ? $user_id : '' }}").trigger('change');
</script>
@endsection


