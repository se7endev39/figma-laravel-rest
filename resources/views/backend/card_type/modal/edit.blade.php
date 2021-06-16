<form method="post" class="ajax-submit" autocomplete="off" action="{{ action('CardTypeController@update', $id) }}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">				
	
	<div class="col-md-12">
	    <div class="form-group">
			<label class="control-label">{{ _lang('Card Type') }}</label>						
			<input type="text" class="form-control" name="card_type" value="{{ $cardtype->card_type }}" required>
	    </div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Currency') }}</label>						
			<select class="form-control select2" name="currency_id" required>
				<option value="">{{ _lang('Select Currency') }}</option>
				{{ create_option('currency', 'id', 'name', $cardtype->currency_id, array('status = ' => 1)) }}
			</select>		
		</div>
	</div>

				
	<div class="form-group">
	  <div class="col-md-12">
		<button type="submit" class="btn btn-primary">{{ _lang('Update') }}</button>
	  </div>
	</div>
</form>

