@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="credit-card"></i></div>
				<span>{{ _lang('Fees') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-12">
			<div class="card no-export">
				<div class="card-body">
					<h4 class="card-title"><span class="panel-title">{{ _lang('Fees Management') }}</span>
						<a href="{{ route('custom_fees.create') }}" class="btn btn-primary btn-sm float-right">{{ _lang('Add New') }}</a>
					</h4>
					<table id="custom_fees_table" class="table table-bordered">
						<thead>
							<tr>
								<th>{{ _lang('Title') }}</th>
								<th>{{ _lang('Amount') }}</th>
								<th>{{ _lang('Note') }}</th>
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
	$('#custom_fees_table').DataTable({
		processing: true,
		serverSide: true,
		ajax: '{{ url('admin/custom_fees/get_table_data') }}',
		"columns" : [
			{ data : 'title', name : 'title' },
			{ data : 'amount', name : 'amount' },
			{ data : 'note', name : 'note' },
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