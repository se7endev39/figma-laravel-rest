@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="info"></i></div>
				<span>{{ _lang('Transfer Request') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card">

				<div class="card-body">
				
					<h4 class="card-title"><span class="panel-title">{{ _lang('Transfer Request') }}</span></h4>

					<table class="table table-striped" id="recent_transactions">
						<thead>
							<th>{{ _lang('ID') }}</th>
							<th>{{ _lang('Date') }}</th>
							<th>{{ _lang('Account') }}</th>
							<th>{{ _lang('DR/CR') }}</th>
							<th class="text-right">{{ _lang('Amount') }}</th>
							<th>{{ _lang('Type') }}</th>
							<th>{{ _lang('Status') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
						</thead>
						<tbody>
							@foreach($transactions as $transaction)
							<tr id="row_{{ $transaction->id }}">
								<td class="id">{{ $transaction->id }}</td>
								<td class="created_at">{{ $transaction->created_at }}</td>
								<td class="account_id">{{ $transaction->account->account_number }}</td>
								<td class="dr_cr">
								    @if($transaction->dr_cr == 'dr')
										<span class="badge badge-danger">{{ _lang('Debit') }}</span>
									@elseif($transaction->dr_cr == 'cr')
										<span class="badge badge-success">{{ _lang('Credit') }}</span>
									@endif
								</td>
								<td class="amount text-right {{ $transaction->dr_cr == 'cr' ? 'text-green' : 'text-red' }}"><b>{{ $transaction->account->account_type->currency->name.' '.decimalPlace($transaction->amount) }}</b></td>
                                <td class="type">{{ ucwords(str_replace('_',' ',$transaction->type)) }}</td>
								<td class="status">
								   @if($transaction->status == 'pending')
										<span class="badge badge-warning">{{ _lang('Pending') }}</span>
									@elseif($transaction->status == 'complete')
										<span class="badge badge-success">{{ _lang('Completed') }}</span>
									@elseif($transaction->status == 'reject')
										<span class="badge badge-danger">{{ _lang('Rejected') }}</span>
									@endif
								</td>

								<td class="text-center">
									<button class="btn btn-primary btn-sm ajax-modal" data-title="{{ _lang('View Transaction Details') }}" data-href="{{ url('admin/transfer_request/' . $status . '/' . $transaction->id) }}"><i class="fas fa-eye"></i>&nbsp;{{ _lang('View') }}</button>
									<a class="btn btn-success btn-sm" href="{{ url('admin/transfer/action/' . $transaction->id . '/approve') }}"><i class="far fa-check-square"></i>&nbsp;{{ _lang('Approve') }}</a>
									<a class="btn btn-danger btn-sm" href="{{ url('admin/transfer/action/' . $transaction->id . '/reject') }}"><i class="far fa-times-circle"></i>&nbsp;{{ _lang('Reject') }}</a>
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

@section('js-script')
<script>
$("#recent_transactions").DataTable({
	responsive: true,
	"bAutoWidth":false,
	stateSave: true,
	"language": {
	   "decimal":        "",
	   "emptyTable":     "{{ _lang('No Data Found') }}",
	   "info":           "{{ _lang('Showing') }} _START_ {{ _lang('to') }} _END_ {{ _lang('of') }} _TOTAL_ {{ _lang('Entries') }}",
	   "infoEmpty":      "{{ _lang('Showing 0 To 0 Of 0 Entries') }}",
	   "infoFiltered":   "(filtered from _MAX_ total entries)",
	   "infoPostFix":    "",
	   "thousands":      ",",
	   "lengthMenu":     "{{ _lang('Show') }} _MENU_ {{ _lang('Entries') }}",
	   "loadingRecords": "{{ _lang('Loading...') }}",
	   "processing":     "{{ _lang('Processing...') }}",
	   "search":         "{{ _lang('Search') }}",
	   "zeroRecords":    "{{ _lang('No matching records found') }}",
	   "paginate": {
		  "first":      "{{ _lang('First') }}",
		  "last":       "{{ _lang('Last') }}",
		  "next":       "{{ _lang('Next') }}",
		  "previous":   "{{ _lang('Previous') }}"
	  },
	  "aria": {
		  "sortAscending":  ": activate to sort column ascending",
		  "sortDescending": ": activate to sort column descending"
	  }
  },
});
</script>
@endsection

