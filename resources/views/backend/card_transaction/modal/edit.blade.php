<form method="post" class="ajax-submit" autocomplete="off" action="{{ action('CardTransactionController@update', $id) }}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">				

	<div class="col-md-12">
	  <div class="form-group">
		<label class="control-label">{{ _lang('Card Number') }}</label>						
		<select class="form-control select2" name="card_id" value="{{ old('card_id') }}" required>
            <option value="">{{ _lang('Select Card Number') }}</option>
            @foreach(\App\Card::where('status',1)->get() as $card)
                <option value="{{ $card->id }}" {{ $cardtransaction->card_id == $card->id ? 'selected' : '' }}>{{ $card->card_number.' - '.$card->card_type->card_type.' ('.$card->card_type->currency->name.')' }}</option>
            @endforeach
		</select>	
	  </div>
	</div>

	<div class="col-md-12">
	  <div class="form-group">
		<label class="control-label">{{ _lang('DR/CR') }}</label>						
		<select class="form-control" name="dr_cr" required>
            <option value="cr" {{ $cardtransaction->dr_cr == 'cr' ? 'selected' : '' }}>{{ _lang('Credit') }}</option>
            <option value="dr" {{ $cardtransaction->dr_cr == 'dr' ? 'selected' : '' }}>{{ _lang('Debit') }}</option>
		</select>	
	  </div>
	</div>


	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Amount') }}</label>						
			<input type="text" class="form-control float-field" name="amount" value="{{ $cardtransaction->amount }}" required>
		</div>
	</div>

	<div class="col-md-12">
	  <div class="form-group">
		<label class="control-label">{{ _lang('Status') }}</label>						
		<select class="form-control" name="dr_cr" required>
            <option value="1" {{ $cardtransaction->dr_cr == '1' ? 'selected' : '' }}>{{ _lang('Completed') }}</option>
            <option value="0" {{ $cardtransaction->dr_cr == '0' ? 'selected' : '' }}>{{ _lang('Pending') }}</option>
            <option value="2" {{ $cardtransaction->dr_cr == '2' ? 'selected' : '' }}>{{ _lang('Rejected') }}</option>
		</select>	
	  </div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Note') }}</label>						
			<textarea class="form-control" name="note">{{ $cardtransaction->note }}</textarea>
		</div>
	</div>
				
	<div class="form-group">
	  <div class="col-md-12">
		<button type="submit" class="btn btn-primary">{{ _lang('Update') }}</button>
	  </div>
	</div>
</form>

