@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="plus-circle"></i></div>
				<span>{{ _lang('Add Currency') }}</span>
			</h1>
		</div>
	</div>
</div>


<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title panel-title">{{ _lang('Add Currency') }}</h4>
					<form method="post" class="validate" autocomplete="off" action="{{ route('currency.store') }}" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="row">
							<div class="col-md-6">
								<div class="col-md-12">
								  <div class="form-group">
									<label class="control-label">{{ _lang('Currency') }}</label>						
									<input type="text" class="form-control" name="name" maxlength="3" value="{{ old('name') }}" required>
								  </div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Base Currency') }}</label>						
										<select class="form-control" name="base_currency" required>
											<option value="0">{{ _lang('No') }}</option>
											<option value="1">{{ _lang('Yes') }}</option>
										</select>
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Exchange Rate') }}</label>						
										<input type="text" class="form-control float-field" name="exchange_rate" value="{{ old('exchange_rate',0) }}" required>
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Status') }}</label>						
										<select class="form-control" name="status" required>
											<option value="1">{{ _lang('Active') }}</option>
											<option value="0">{{ _lang('In-Active') }}</option>
										</select>
									</div>
								</div>


								<div class="col-md-12">
									<div class="form-group">
										<button type="reset" class="btn btn-danger">{{ _lang('Reset') }}</button>
										<button type="submit" class="btn btn-primary">{{ _lang('Save') }}</button>
									</div>
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


