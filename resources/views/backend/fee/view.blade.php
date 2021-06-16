@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="credit-card"></i></div>
				<span>{{ _lang('View Fee') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
					 <h4 class="card-title panel-title">{{ _lang('View Fee') }}</h4>
					<table class="table table-bordered">
						<tr><td>{{ _lang('Title') }}</td><td>{{ $fee->title }}</td></tr>
						<tr><td>{{ _lang('Amount') }}</td><td>{{ $fee->amount }}</td></tr>
						<tr><td>{{ _lang('Note') }}</td><td>{{ $fee->note }}</td></tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection


