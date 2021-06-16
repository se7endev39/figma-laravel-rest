@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="edit"></i></div>
				<span>{{ _lang('Update Collateral') }}</span>
			</h1>
		</div>
	</div>
</div>


<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-8">
			<div class="card">
				<div class="card-header">
					<span class="panel-title">{{ _lang('Update Collateral') }}</span>
				</div>
				<div class="card-body">
					<form method="post" class="validate" autocomplete="off" action="{{ action('LoanCollateralController@update', $id) }}" enctype="multipart/form-data">
						{{ csrf_field()}}
						<input name="_method" type="hidden" value="PATCH">				
						<div class="row">

							<div class="col-md-12">
							    <div class="form-group">
								   <label class="control-label">{{ _lang('Name') }}</label>						
								   <input type="text" class="form-control" name="name" value="{{ $loancollateral->name }}" required>
							    </div>
							</div>

							<div class="col-md-12">
							    <div class="form-group">
								   <label class="control-label">{{ _lang('Collateral Type') }}</label>						
								   <input type="text" class="form-control" name="collateral_type" value="{{ $loancollateral->collateral_type }}" required>
							    </div>
							</div>

							<div class="col-md-12">
							    <div class="form-group">
								   <label class="control-label">{{ _lang('Serial Number') }}</label>						
								   <input type="text" class="form-control" name="serial_number" value="{{ $loancollateral->serial_number }}">
							    </div>
							</div>

							<div class="col-md-12">
							    <div class="form-group">
								   <label class="control-label">{{ _lang('Estimated Price') }}</label>						
								   <input type="text" class="form-control" name="estimated_price" value="{{ $loancollateral->estimated_price }}" required>
							    </div>
							</div>

							<div class="col-md-12">
							    <div class="form-group">
								   <label class="control-label">{{ _lang('Attachment') }}</label>						
								   <input type="file" class="dropify" name="attachments">
							    </div>
							</div>

							<div class="col-md-12">
							    <div class="form-group">
								   <label class="control-label">{{ _lang('Description') }}</label>						
								   <textarea class="form-control" name="description">{{ $loancollateral->description }}</textarea>
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


