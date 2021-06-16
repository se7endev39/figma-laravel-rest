@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="credit-card"></i></div>
				<span>{{ _lang('Create Fee') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-6">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title panel-title">{{ _lang('Create Fee') }}</h4>
					<form method="post" class="validate" autocomplete="off" action="{{ route('custom_fees.store') }}" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label">{{ _lang('Account Type') }}</label>						
									<select class="form-control select2" name="account_type" id="account_type" required>
								        <option value="">{{ _lang('Select Account Type') }}</option>
										@foreach ( \App\AccountType::all() as $account_type )
											<option value="{{ $account_type->id }}" {{ old('account_type') == $account_type->id ? 'selected' : '' }}>{{ $account_type->account_type.' ('.$account_type->currency->name.')' }}</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
								    <a href="" id="select-all" class="float-right">{{ _lang('Select All') }}</a>
									<label class="control-label">{{ _lang('Accounts') }}</label>						
									<select class="form-control" name="accounts[]" id="account_list" multiple="true" required>
								        
									</select>
								</div>
							</div>
											
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label">{{ _lang('Title') }}</label>						
									<input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
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
									<label class="control-label">{{ _lang('Note') }}</label>						
									<textarea class="form-control" name="note">{{ old('note') }}</textarea>
								</div>
							</div>

							
							<div class="col-md-12">
								<div class="form-group">
									<button type="submit" class="btn btn-primary">{{ _lang('Process') }}</button>
								</div>
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
$(document).on('change','#account_type',function(){
   var account_type_id = $(this).val();

   if( account_type_id != '' ){
       $.ajax({
          url: "{{ url('admin/accounts/get_by_account_type') }}/" + account_type_id,
          beforeSend: function(){
			  $("#preloader").css("display","block"); 
          },success: function(data){
          	  $("#preloader").css("display","none");

              var json = JSON.parse(data);
              $("#account_list").find('option').remove();

              jQuery.each( json, function( i, val ) {
				  $("#account_list").append("<option value='" + val.id + ',' + val.user_id + "'>" + val.account_number + "</option>");
			  });
          }
       });
   }
});

var $select_action = false;
$(document).on('click','#select-all',function(event){
	event.preventDefault();
	$select_action = $select_action == false ? true : false;
	$('#account_list option').prop('selected', $select_action);
});	
</script>
@endsection


