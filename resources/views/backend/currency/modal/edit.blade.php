<form method="post" class="ajax-submit" autocomplete="off" action="{{action('CurrencyController@update', $id)}}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">				

	<div class="col-md-12">
	  <div class="form-group">
		<label class="control-label">{{ _lang('Currency') }}</label>						
		<input type="text" class="form-control" name="name" maxlength="3" value="{{ $currency->name }}" required>
	  </div>
	</div>

	<div class="col-md-12">
	  <div class="form-group">
		<label class="control-label">{{ _lang('Base Currency') }}</label>						
		<select class="form-control" name="base_currency" id="base_currency" required>
		   <option value="0" {{ $currency->base_currency == 0 ? 'selected' : '' }}>{{ _lang('No') }}</option>
		   <option value="1" {{ $currency->base_currency == 1 ? 'selected' : '' }}>{{ _lang('Yes') }}</option>
		</select>
	  </div>
	</div>

	<div class="col-md-12">
	 <div class="form-group">
		<label class="control-label">{{ _lang('Exchange Rate') }}</label>						
		<input type="text" class="form-control float-field" name="exchange_rate" value="{{ $currency->exchange_rate }}" required>
	 </div>
	</div>

	<div class="col-md-12">
	  <div class="form-group">
		<label class="control-label">{{ _lang('Status') }}</label>						
		<select class="form-control" name="status" id="status" required>
		   <option value="1" {{ $currency->status==1 ? 'selected' : '' }}>{{ _lang('Active') }}</option>
		   <option value="0" {{ $currency->status==0 ? 'selected' : '' }}>{{ _lang('In-Active') }}</option>
		</select>
	  </div>
	</div>

				
	<div class="form-group">
	  <div class="col-md-12">
		<button type="submit" class="btn btn-primary">{{ _lang('Update') }}</button>
	  </div>
	</div>
</form>

