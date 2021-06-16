@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="file-text"></i></div>
				<span>{{ _lang('Submit Documents') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			@if(Session::has('document_success'))
			<div class="alert alert-success">
				<span>{{ session('document_success') }}</span>
			</div>  
			@endif
			<div class="card">
				<div class="card-body">
					<h4 class="card-title panel-title">{{ _lang('Submit Documents') }}</h4>

					@if(Auth::user()->user_type == 'user' && Auth::user()->account_status != 'Verified' && Auth::user()->document_submitted_at != '')
						<div class="alert alert-danger">
							<span><i class="mdi mdi-information-outline"></i> {{ _lang('You have already submitted your documents ! You will be notified soon after reviewing your documents.') }}</span>
						</div>
					@else
					<form method="post" autocomplete="off" action="{{ url('user/submit_documents') }}" enctype="multipart/form-data">
						<div class="row">
							{{ csrf_field() }}

							<div class="col-md-12">					 
								<div class="form-group">
									<label class="control-label">{{ _lang('Scan Copy of NID / Passport / Driving License') }}</label>						
									<input type="file" class="dropify" name="nid_passport" data-allowed-file-extensions="png jpg jpeg pdf PNG JPG JPEG PDF" data-default-file="" required="true">
								</div>
							</div>	

							<div class="col-md-12">					 
								<div class="form-group">
									<label class="control-label">{{ _lang('Scan Copy of Electric Bill / Bank Statement') }}</label>						
									<input type="file" class="dropify" name="electric_bill" data-allowed-file-extensions="png jpg jpeg pdf PNG JPG JPEG PDF" data-default-file="" required="true">
								</div>
							</div>	

							<div class="col-md-12">
								<div class="form-group">
									<button type="submit" class="btn btn-primary">{{ _lang('Submit Documents') }}</button>	
								</div>
							</div>

						</div>
					</form>
					@endif

				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js-script')
<script>
	$("#user_type").val("{{ old('user_type') }}");
</script>
@endsection


