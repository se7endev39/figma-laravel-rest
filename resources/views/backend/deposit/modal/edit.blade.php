<form method="post" class="ajax-submit" autocomplete="off" action="{{action('DepositController@update', $id)}}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">				
	
	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Select User') }}</label>						
			<select class="form-control select2" name="user_id" id="user_id" required>
				<option value="">{{ _lang('Select User') }}</option>
				@foreach(get_table('users',array('user_type='=>'user')) as $user )
				<option value="{{ $user->id }}" {{ $deposit->user_id == $user->id ? 'selected' : '' }}>{{ $user->first_name.' '.$user->last_name }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Select Account') }}</label>						
			<select class="form-control select2" name="account_id" id="account_id" required>
				@foreach(\App\Account::where('user_id',$deposit->user_id)->get() as $account )
					<option value="{{ $account->id }}" {{ $deposit->account_id == $account->id ? 'selected' : '' }}>{{ $account->account_number.' - '.$account->account_type->account_type.' ('.$account->account_type->currency->name.')' }}</option>
				@endforeach
			</select>
		</div>
	</div>
	
	<div class="col-md-12">
	 <div class="form-group">
		<label class="control-label">{{ _lang('Amount') }}</label>						
		<input type="text" class="form-control float-field" name="amount" value="{{ $deposit->amount }}" required>
	 </div>
	</div>

	<div class="col-md-12">
	 <div class="form-group">
		<label class="control-label">{{ _lang('Note') }}</label>						
		<textarea class="form-control" name="note">{{ $deposit->note }}</textarea>
	 </div>
	</div>

	<div class="col-md-12">
	 <div class="form-group">
		<label class="control-label">{{ _lang('Status') }}</label>						
		<select class="form-control" name="status" id="status">
			<option value="0">{{ _lang('Pending') }}</option>
			<option value="1">{{ _lang('Completed') }}</option>
			<option value="2">{{ _lang('Cancel') }}</option>
		</select>
	</div>
	</div>

	<div class="col-md-12">
	  <div class="form-group">
		<button type="submit" class="btn btn-primary">{{ _lang('Update Deposit') }}</button>
	  </div>
	</div>
</form>

<script>
$("#status").val("{{ $deposit->status }}");

$("#status").val("{{ $deposit->status }}");
	
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
