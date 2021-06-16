<form method="post" class="ajax-submit" autocomplete="off" action="{{route('currency.store')}}" enctype="multipart/form-data">
	{{ csrf_field() }}
	
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
</form>
