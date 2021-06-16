<form method="post" class="ajax-submit" autocomplete="off" action="{{ action('IncomeController@update', $id) }}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">				
	
	<div class="col-md-12">
		<div class="form-group">
		   <label class="control-label">{{ _lang('Date') }}</label>						
		   <input type="text" class="form-control datepicker" name="trans_date" value="{{ $financetransaction->trans_date }}" required>
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Income Category') }}</label>						
			<select class="form-control" name="chart_of_account_id"  required>
				<option value="">{{ _lang('Select One') }}</option>
				{{ create_option('chart_of_accounts','id','name',old('chart_of_account_id'), array('type=' => 'income')) }}
			</select>
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
		   <label class="control-label">{{ _lang('Amount').' ('.get_base_currency().')'  }}</label>						
		   <input type="number" class="form-control" name="amount" value="{{ $financetransaction->amount }}" required>
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
		   <label class="control-label">{{ _lang('Reference') }}</label>						
		   <input type="text" class="form-control" name="reference" value="{{ $financetransaction->reference }}">
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
		   <label class="control-label">{{ _lang('Note') }}</label>						
		   <textarea class="form-control" name="note">{{ $financetransaction->note }}</textarea>
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
		   <label class="control-label">{{ _lang('Attachment') }}</label>						
		   <input type="file" class="form-control dropify" name="attachment">
		</div>
	</div>

	
	<div class="form-group">
	    <div class="col-md-12">
		    <button type="submit" class="btn btn-primary">{{ _lang('Update') }}</button>
	    </div>
	</div>
</form>

