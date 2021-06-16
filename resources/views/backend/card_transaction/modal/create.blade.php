<form method="post" class="ajax-submit" autocomplete="off" action="{{ route('card_transactions.store') }}" enctype="multipart/form-data">
	{{ csrf_field() }}
	
	<div class="col-md-12">
	  <div class="form-group">
		<label class="control-label">{{ _lang('Card Number') }}</label>						
		<select class="form-control select2" name="card_id" value="{{ old('card_id') }}" required>
            <option value="">{{ _lang('Select Card Number') }}</option>
            @foreach(\App\Card::where('status',1)->get() as $card)
                <option value="{{ $card->id }}">{{ $card->card_number.' - '.$card->card_type->card_type.' ('.$card->card_type->currency->name.')' }}</option>
            @endforeach
		</select>	
	  </div>
	</div>

	<div class="col-md-12">
	  <div class="form-group">
		<label class="control-label">{{ _lang('DR/CR') }}</label>						
		<select class="form-control" name="dr_cr" required>
            <option value="cr" {{ old('dr_cr') == 'cr' ? 'selected' : '' }}>{{ _lang('Credit') }}</option>
            <option value="dr" {{ old('dr_cr') == 'dr' ? 'selected' : '' }}>{{ _lang('Debit') }}</option>
		</select>	
	  </div>
	</div>


	<div class="col-md-12">
	  <div class="form-group">
		<label class="control-label">{{ _lang('Amount') }}</label>						
		<input type="text" class="form-control float-field" name="amount" value="{{ old('amount') }}" required>
	  </div>
	</div>

	<div class="col-md-12">
	  <div class="form-group">
		<label class="control-label">{{ _lang('Status') }}</label>						
		<select class="form-control" name="dr_cr" required>
            <option value="1" {{ old('dr_cr') == '1' ? 'selected' : '' }}>{{ _lang('Completed') }}</option>
            <option value="0" {{ old('dr_cr') == '0' ? 'selected' : '' }}>{{ _lang('Pending') }}</option>
            <option value="2" {{ old('dr_cr') == '2' ? 'selected' : '' }}>{{ _lang('Rejected') }}</option>
		</select>	
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
	    <button type="reset" class="btn btn-danger">{{ _lang('Reset') }}</button>
		<button type="submit" class="btn btn-primary">{{ _lang('Save') }}</button>
	  </div>
	</div>
</form>
