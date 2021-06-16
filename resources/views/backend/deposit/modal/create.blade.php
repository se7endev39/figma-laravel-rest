<form method="post" class="ajax-submit" autocomplete="off" action="{{route('deposit.store')}}" enctype="multipart/form-data">
	{{ csrf_field() }}
	
	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Select User') }}</label>						
			<select class="form-control select2" name="user_id" id="user_id" required>
				<option value="">{{ _lang('Select User') }}</option>
				@foreach(get_table('users',array('user_type='=>'user')) as $user )
				<option value="{{ $user->id }}">{{ $user->first_name.' '.$user->last_name }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Select Account') }}</label>						
			<select class="form-control select2" name="account_id" id="account_id" required>
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
			<label class="control-label">{{ _lang('Deposit Type') }}</label>						
			<select class="form-control" name="type" required>
				<option value="deposit">{{ _lang('Deposit') }}</option>
				<option value="wire_transfer">{{ _lang('Incoming Wire Transfer') }}</option>
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
		<button type="submit" class="btn btn-primary">{{ _lang('Make Deposit') }}</button>
	  </div>
	</div>
</form>


<script>
$(document).on('change','#user_id',function(){
   var user_id = $(this).val();

   if( user_id != '' ){
       $.ajax({
          url: "{{ url('admin/accounts/get_by_user_id') }}/" + user_id,
          beforeSend: function(){
			  $("#preloader").css("display","block"); 
          },success: function(data){
          	  $("#preloader").css("display","none");
              var json = JSON.parse(data);
              $("#account_id").find('option').remove();

              jQuery.each( json, function( i, val ) {
				 $("#account_id").append("<option value='" + val.id + "'>" + val.account_number + ' - ' + val.account_type.account_type + ' (' + val.account_type.currency.name +")</option>");
			  });
          }
       });
   }
});
</script>