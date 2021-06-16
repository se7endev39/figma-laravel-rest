@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="align-left"></i></div>
				<span>{{ _lang('Loan List') }}</span>
			</h1>
		</div>
	</div>
</div>


<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">

				<div class="card-header d-flex justify-content-between align-items-center">
					<span class="panel-title">{{ _lang('Loan List') }}</span>
					<a class="btn btn-primary btn-sm float-right" href="{{ route('loans.create') }}">{{ _lang('Add New') }}</a>
				</div>

				<div class="card-body">
					<table id="loans_table" class="table table-bordered">
						<thead>
						    <tr>
							    <th>{{ _lang('Loan ID') }}</th>
							    <th>{{ _lang('Loan Product') }}</th>
								<th>{{ _lang('Borrower') }}</th>
								<th>{{ _lang('Account') }}</th>
								<th>{{ _lang('Release Date') }}</th>
								<th>{{ _lang('Applied Amount') }}</th>
								<th>{{ _lang('Status') }}</th>
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
	$('#loans_table').DataTable({
		processing: true,
		serverSide: true,
		ajax: '{{ url('admin/loans/get_table_data') }}',
		"columns" : [
			{ data : 'loan_id', name : 'loan_id' },
			{ data : 'loan_product.name', name : 'loan_product.name' },
			{ data : 'borrower.first_name', name : 'borrower.first_name' },
			{ data : 'account.account_number', name : 'account.account_number' },
			{ data : 'release_date', name : 'release_date' },
			{ data : 'applied_amount', name : 'applied_amount' },
			{ data : 'status', name : 'status' },
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