<form method="post" class="ajax-submit" autocomplete="off" action="{{action('WithdrawController@update', $id)}}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">				
	
	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('User') }}</label>						
			<select class="form-control" name="user_id" id="user_id" disabled='true'>
				<option value="">{{ _lang('Select User') }}</option>
				@foreach(get_table('users',array('user_type='=>'user')) as $user )
				<option value="{{ $user->id }}" {{ $withdraw->user_id == $user->id ? 'selected' : '' }}>{{ $user->first_name.' '.$user->last_name }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Account Details') }}</label>	
			@php $account = $withdraw->account->account_number.' - '.$withdraw->account->account_type->account_type.' ('.$withdraw->account->account_type->currency->name.' '. get_account_balance($withdraw->account_id, $withdraw->user_id) .')' @endphp					
			<input type="text" class="form-control" name="account_id" id="account_id" value="{{ $account }}" disabled='true'>
		</div>
	</div>

	<div class="col-md-12">
	 <div class="form-group">
		<label class="control-label">{{ _lang('Amount') }}</label>						
		<input type="text" class="form-control" name="amount" value="{{ $withdraw->amount }}" required>
	 </div>
	</div>

	<div class="col-md-12">
	 <div class="form-group">
		<label class="control-label">{{ _lang('Note') }}</label>						
		<input type="text" class="form-control" name="note" value="{{ $withdraw->note }}">
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
		<button type="submit" class="btn btn-primary">{{ _lang('Update') }}</button>
	  </div>
	</div>
</form>

<script>
$("#status").val("{{ $withdraw->status }}");
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
				 $("#account_id").append("<option value='" + val.id + "'>" + val.account_number + ' - ' + val.account_type.account_type + ' (' + val.account_type.currency.name +' '+ val.balance + ")</option>");
			  });
          }
       });
   }
});
</script>