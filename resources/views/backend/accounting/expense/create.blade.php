@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="plus-circle"></i></div>
				<span>{{ _lang('Add Expense') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-6">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title panel-title">{{ _lang('Add Expense') }}</h4>
				    <form method="post" class="validate" autocomplete="off" action="{{ route('expense.store') }}" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="row">
						    <div class="col-md-12">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Date') }}</label>	
							        <input type="text" class="form-control datepicker" name="trans_date" value="{{ old('trans_date') }}" required>
						        </div>
						    </div>

							<div class="col-md-12">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Expense Category') }}</label>
							        <select class="form-control" name="chart_of_account_id"  required>
						                <option value="">{{ _lang('Select One') }}</option>
										{{ create_option('chart_of_accounts','id','name',old('chart_of_account_id'), array('type=' => 'expense')) }}
									</select>
								</div>
						    </div>

							<div class="col-md-12">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Amount').' ('.get_base_currency().')'  }}</label>
							        <input type="number" class="form-control" name="amount" value="{{ old('amount') }}" required>
						        </div>
						    </div>

							<div class="col-md-12">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Reference') }}</label>	
							        <input type="text" class="form-control" name="reference" value="{{ old('reference') }}">
						        </div>
						    </div>

							<div class="col-md-12">
							    <div class="form-group">
								    <label class="control-label">{{ _lang('Note') }}</label>	
								    <textarea class="form-control" name="note">{{ old('note') }}</textarea>
							    </div>
							</div>

							<div class="col-md-12">
							    <div class="form-group">
								    <label class="control-label">{{ _lang('Attachment') }}</label>		
								    <input type="file" class="form-control dropify" name="attachment" >
							    </div>
							</div>

							
							<div class="col-md-12">
							    <div class="form-group">
								    <button type="submit" class="btn btn-primary">{{ _lang('Save') }}</button>
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


