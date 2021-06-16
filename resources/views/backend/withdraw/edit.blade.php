@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="minus-circle"></i></div>
				<span>{{ _lang('Update Withdraw') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-8">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title panel-title">{{ _lang('Update Withdraw') }}</h4>
					<form method="post" class="form-horizontal validate" autocomplete="off" action="{{action('WithdrawController@update', $id)}}">
						{{ csrf_field()}}
						<input name="_method" type="hidden" value="PATCH">				


						<div class="form-group row">
							<label class="col-md-4 control-label">{{ _lang('User') }}</label>						
							<div class="col-md-8">
								<select class="form-control select2" name="user_id" id="user_id" disabled='true'>
									<option value="">{{ _lang('Select User') }}</option>
									@foreach(get_table('users',array('user_type='=>'user')) as $user )
									<option value="{{ $user->id }}" {{ $withdraw->user_id == $user->id ? 'selected' : '' }}>{{ $user->first_name.' '.$user->last_name }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-4 control-label">{{ _lang('Account Details') }}</label>	
							@php $account = $withdraw->account->account_number.' - '.$withdraw->account->account_type->account_type.' ('.$withdraw->account->account_type->currency->name.' '. get_account_balance($withdraw->account_id, $withdraw->user_id) .')' @endphp					
							<div class="col-md-8">
								<input type="text" class="form-control" name="account_id" id="account_id" value="{{ $account }}" disabled='true'>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-4 control-label">{{ _lang('Amount') }}</label>		
							<div class="col-md-8">				
								<input type="text" class="form-control" name="amount" value="{{ $withdraw->amount }}" required>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-4 control-label">{{ _lang('Note') }}</label>	
							<div class="col-md-8">					
								<input type="text" class="form-control" name="note" value="{{ $withdraw->note }}">
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-4 control-label">{{ _lang('Status') }}</label>	
							<div class="col-md-8">					
								<select class="form-control" name="status" id="status">
									<option value="0">{{ _lang('Pending') }}</option>
									<option value="1">{{ _lang('Completed') }}</option>
									<option value="2">{{ _lang('Cancel') }}</option>
								</select>
							</div>
						</div>

						<div class="form-group row">
							<div class="col-md-8 offset-md-4">
								<button type="submit" class="btn btn-primary">{{ _lang('Update') }}</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('js-script')
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
@endsection


