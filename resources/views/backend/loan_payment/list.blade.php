@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="align-left"></i></div>
				<span>{{ _lang('Loan Payment List') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
			    <div class="card-header d-flex justify-content-between align-items-center">
					<span class="panel-title">{{ _lang('Loan Payment List') }}</span>
					<a class="btn btn-primary btn-sm float-right" href="{{ route('loan_payments.create') }}">{{ _lang('Add New') }}</a>
				</div>
				<div class="card-body">
					<table id="loan_payments_table" class="table table-bordered">
						<thead>
						    <tr>
							    <th>{{ _lang('Loan ID') }}</th>
								<th>{{ _lang('Payment Date') }}</th>
								<th>{{ _lang('Late Penalties') }}</th>
								<th>{{ _lang('Amount To Pay') }}</th>
								<th>{{ _lang('Remarks') }}</th>
								<th class="text-center">{{ _lang('Action') }}</th>
						    </tr>
						</thead>
						<tbody>
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
$(function() {
	$('#loan_payments_table').DataTable({
		processing: true,
		serverSide: true,
		ajax: '{{ url('admin/loan_payments/get_table_data') }}',
		"columns" : [
			{ data : 'loan.loan_id', name : 'loan.loan_id' },
			{ data : 'paid_at', name : 'paid_at' },
			{ data : 'late_penalties', name : 'late_penalties' },
			{ data : 'amount_to_pay', name : 'amount_to_pay' },
			{ data : 'remarks', name : 'remarks' },
			{ data : "action", name : "action" },
		],
		responsive: true,
		"bStateSave": true,
		"bAutoWidth":false,	
		"ordering": false,
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
		  }
		} 
	});
});
</script>
@endsection