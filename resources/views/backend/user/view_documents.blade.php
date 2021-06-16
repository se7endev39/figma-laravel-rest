@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="user"></i></div>
				<span>{{ _lang('Documents of').' '.$user->first_name.' '.$user->last_name }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">

	<div class="row">
		<div class="col-12">
			@if(Session::has('varified_success'))
				<div class="alert alert-success">
					<span>{{ session('varified_success') }}</span>
				</div>  
			@endif
			<div class="card">

				<div class="card-body">
				
					<h4 class="card-title">
						<span class="panel-title">{{ _lang('Documents of').' '.$user->first_name.' '.$user->last_name }}</span>
						
						@if($user->account_status != 'Verified')
							<a href="{{ url('admin/users/varify/'.$user->id) }}" class="btn btn-success btn-sm float-right"><i class="mdi mdi-check-all"></i> {{ _lang('Click to Verify') }}</a>
						@else
							<a href="{{ url('admin/users/unvarify/'.$user->id) }}" class="btn btn-danger btn-sm float-right"><i class="mdi mdi-close-circle-outline"></i> {{ _lang('Click to Unverify') }}</a>
						@endif
						
					</h4>

					<table class="table table-bordered data-table">
						<thead>
						<tr>
							<th>{{ _lang('Document Name') }}</th>
							<th>{{ _lang('Document File') }}</th>
							<th>{{ _lang('Submitted At') }}</th>
						</tr>
						</thead>
						<tbody>
						
						@foreach($documents as $document)
							<tr>
								<td>{{ $document->document_name }}</td>
								<td><a target="_blank" href="{{ asset('uploads/documents/'.$document->document ) }}">{{ $document->document }}</a></td>
								<td>{{ date('d M, Y H:i:s',strtotime($document->created_at)) }}</td>																			
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


