@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="credit-card"></i></div>
				<span>{{ _lang('Update Fee') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-6">
			<div class="card">
				<div class="card-body">
				    <h4 class="card-title panel-title">{{ _lang('Update Fee') }}</h4>
					<form method="post" class="validate" autocomplete="off" action="{{ action('FeeController@update', $id) }}" enctype="multipart/form-data">
						{{ csrf_field()}}
						<input name="_method" type="hidden" value="PATCH">				
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
								   <label class="control-label">{{ _lang('Title') }}</label>						
								   <input type="text" class="form-control" name="title" value="{{ $fee->title }}" required>
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group">
								   <label class="control-label">{{ _lang('Amount') }}</label>						
								   <input type="text" class="form-control float-field" name="amount" value="{{ $fee->amount }}" required>
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group">
								   <label class="control-label">{{ _lang('Note') }}</label>						
								   <textarea class="form-control" name="note">{{ $fee->note }}</textarea>
								</div>
							</div>

							
							<div class="col-md-12">
								<div class="form-group">
									<button type="submit" class="btn btn-primary">{{ _lang('Update') }}</button>
								</div>
							</div>	
						</div>	
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection


