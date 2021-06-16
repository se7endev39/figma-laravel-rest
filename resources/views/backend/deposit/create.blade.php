@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="plus-circle"></i></div>
				<span>{{ _lang('Make Deposit') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-8">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title panel-title">{{ _lang('Make Deposit') }}</h4>
					<form method="post" class="form-horizontal validate" autocomplete="off" action="{{ route('deposit.store') }}" enctype="multipart/form-data">
						{{ csrf_field() }}
								
						<div class="form-group row">
							<label class="col-md-4 control-label">{{ _lang('Select User') }}</label>					
							<div class="col-md-8">
								<select class="form-control select2" name="user_id" id="user_id" required>
									<option value="">{{ _lang('Select User') }}</option>
									@foreach(get_table('users',array('user_type='=>'user')) as $user )
									<option value="{{ $user->id }}">{{ $user->first_name.' '.$user->last_name }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-4 control-label">{{ _lang('Select Account') }}</label>						
							<div class="col-md-8">
								<select class="form-control select2" name="account_id" id="account_id" required>
								</select>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-md-4 control-label">{{ _lang('Amount') }}</label>						
							<div class="col-md-8">
								<input type="text" class="form-control float-field" name="amount" value="{{ old('amount') }}" required>
							</div>
						</div>


						<div class="form-group row">
							<label class="col-md-4 control-label">{{ _lang('Deposit Type') }}</label>						
							<div class="col-md-8">
								<select class="form-control" name="type" required>
									<option value="deposit">{{ _lang('Deposit') }}</option>
									<option value="wire_transfer">{{ _lang('Incoming Wire Transfer') }}</option>
								</select>
							</div>
						</div>


						<div class="form-group row">
							<label class="col-md-4 control-label">{{ _lang('Note') }}</label>						
							<div class="col-md-8">
								<textarea class="form-control" name="note">{{ old('note') }}</textarea>
							</div>
						</div>

						<div class="form-group row">
							<div class="col-md-8 offset-md-4">
								<button type="submit" class="btn btn-primary">{{ _lang('Make Deposit') }}</button>
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
@endsection

