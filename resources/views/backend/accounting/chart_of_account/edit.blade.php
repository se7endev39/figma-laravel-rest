@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="home"></i></div>
				<span>{{ _lang('Update Income/Expense Category') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title panel-title">
						{{ _lang('Update Income/Expense Category') }}
					</h4>
					<form method="post" class="validate" autocomplete="off" action="{{ action('ChartOfAccountController@update', $id) }}" enctype="multipart/form-data">
						{{ csrf_field()}}
						<input name="_method" type="hidden" value="PATCH">				
						<div class="row">
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-12">
									    <div class="form-group">
										   <label class="control-label">{{ _lang('Name') }}</label>						
										   <input type="text" class="form-control" name="name" value="{{ $chartofaccount->name }}" required>
									    </div>
									</div>

									<div class="col-md-12">
										<div class="form-group">
											<label class="control-label">{{ _lang('Type') }}</label>						
											<select class="form-control" name="type" required>
												<option value="">{{ _lang('Select One') }}</option>
												<option value="income" {{ $chartofaccount->type == 'income' ? 'selected' : '' }}>{{ _lang('Income') }}</option>
												<option value="expense"  {{ $chartofaccount->type == 'expense' ? 'selected' : '' }}>{{ _lang('Expense') }}</option>
											</select>
										</div>
									</div>

									
									<div class="col-md-12">
									    <div class="form-group">
										    <button type="submit" class="btn btn-primary">{{ _lang('Update') }}</button>
									    </div>
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


