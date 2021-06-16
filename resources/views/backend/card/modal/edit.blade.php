<form method="post" class="ajax-submit" autocomplete="off" action="{{ action('CardController@update', $id) }}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">				
	<div class="row p-2">
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Card Owner') }}</label>						
				<select class="form-control select2" name="user_id" required>
					<option value="">{{ _lang('Select User') }}</option>
					@foreach ( \App\User::where('status',1)->where('user_type','user')->get() as $user )
						<option value="{{ $user->id }}" {{ $card->user_id == $user->id ? 'selected' : '' }}>{{ $user->first_name.' '.$user->last_name }}</option>
					@endforeach
				</select>	
			</div>
		</div>

		<div class="col-md-12">
		   <div class="form-group">
			  <label class="control-label">{{ _lang('Card Number') }}</label>						
			  <input type="text" class="form-control cc" name="card_number" value="{{ $card->card_number }}" required>
		   </div>
		</div>
		
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label">{{ _lang('Card Type') }}</label>						
				<select class="form-control select2" name="card_type_id" required>
					 <option value="">{{ _lang('Select Type') }}</option>
					 @foreach ( \App\CardType::all() as $card_type )
						<option value="{{ $card_type->id }}" {{ $card->card_type_id == $card_type->id ? 'selected' : '' }}>{{ $card_type->card_type.' ('.$card_type->currency->name.')' }}</option>
					 @endforeach
				</select>	
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label">{{ _lang('Status') }}</label>						
				<select class="form-control" name="status" required>
					 <option value="1" {{ $card->status == '1' ? 'selected' : '' }}>{{ _lang('Active') }}</option>
					 <option value="0" {{ $card->status == '0' ? 'selected' : '' }}>{{ _lang('Blocked') }}</option>
				</select>	
			</div>
		</div>

		<div class="col-md-6">
		 <div class="form-group">
			<label class="control-label">{{ _lang('Expiration Date') }}</label>						
			<input type="text" class="form-control datepicker" name="expiration_date" value="{{ $card->expiration_date }}" required>
		 </div>
		</div>

		<div class="col-md-6">
		 <div class="form-group">
			<label class="control-label">{{ _lang('CVV') }}</label>						
			<input type="text" class="form-control cvv" name="cvv" value="{{ $card->cvv }}" required>
		 </div>
		</div>

		<div class="col-md-12">
		 <div class="form-group">
			<label class="control-label">{{ _lang('Note') }}</label>						
			<textarea class="form-control" name="note">{{ $card->note }}</textarea>
		 </div>
		</div>
				
		<div class="form-group">
		  <div class="col-md-12">
			<button type="submit" class="btn btn-primary">{{ _lang('Update') }}</button>
		  </div>
		</div>
	</div>
</form>