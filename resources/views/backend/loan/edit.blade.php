@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="edit"></i></div>
				<span>{{ _lang('Update Loan') }}</span>
			</h1>
		</div>
	</div>
</div>


<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-8">
			<div class="card">
				<div class="card-header">
					<span class="panel-title">{{ _lang('Update Loan') }}</span>
				</div>
				<div class="card-body">
					@if($loan->status == 1)
						<div class="alert alert-warning">
							<strong>{{ _lang('Loan has already approved. You can change only description and remarks') }}</strong>
						</div>
					@endif
					<form method="post" class="validate" autocomplete="off" action="{{ action('LoanController@update', $id) }}" enctype="multipart/form-data">
						{{ csrf_field()}}
						<input name="_method" type="hidden" value="PATCH">				
						<div class="row">
							<div class="col-md-6">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Loan ID') }}</label>						
							        <input type="text" class="form-control" name="loan_id" value="{{ $loan->loan_id }}" required>
						        </div>
						    </div>

							<div class="col-md-6">
							    <div class="form-group">
								    <label class="control-label">{{ _lang('Loan Product') }}</label>						
								    <select class="form-control auto-select select2" data-selected="{{ $loan->loan_product_id }}" name="loan_product_id" {{ $loan->status == 1 ? 'disabled' : 'required' }}>
						                <option value="">{{ _lang('Select One') }}</option>
										{{ create_option('loan_products','id','name',$loan->loan_product_id) }}
								    </select>
							    </div>
							</div>

							<div class="col-md-6">
							    <div class="form-group">
								    <label class="control-label">{{ _lang('Borrower') }}</label>						
								    <select class="form-control auto-select select2" data-selected="{{ $loan->borrower_id }}" name="borrower_id" id="borrower_id"  {{ $loan->status == 1 ? 'disabled' : 'required' }}>
						                <option value="">{{ _lang('Select One') }}</option>
										@foreach(get_table('users',array('user_type='=>'user')) as $user )
											<option value="{{ $user->id }}">{{ $user->first_name.' '.$user->last_name }}</option>
										@endforeach
								    </select>
							    </div>
							</div>

							<div class="col-md-6">
							    <div class="form-group">
								    <label class="control-label">{{ _lang('Account') }}</label>						
								    <select class="form-control auto-select" data-selected="{{ $loan->account_id }}" name="account_id" id="account_id" {{ $loan->status == 1 ? 'disabled' : 'required' }}>
										@foreach(\App\Account::where('user_id', $loan->borrower_id)->get() as $account )
											<option value="{{ $account->id }}" {{ $loan->account_id == $account->id ? 'selected' : '' }}>{{ $account->account_number.' - '.$account->account_type->account_type.' ('.$account->account_type->currency->name.')' }}</option>
										@endforeach
								    </select>
							    </div>
							</div>

							 <div class="col-md-6">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('First Payment Date') }}</label>						
							        <input type="text" class="form-control datepicker" name="first_payment_date" value="{{ $loan->first_payment_date }}"  {{ $loan->status == 1 ? 'disabled' : 'required' }}>
						        </div>
						    </div>

							<div class="col-md-6">
							    <div class="form-group">
								   <label class="control-label">{{ _lang('Release Date') }}</label>						
								   <input type="text" class="form-control datepicker" name="release_date" value="{{ $loan->release_date }}"  {{ $loan->status == 1 ? 'disabled' : 'required' }}>
							    </div>
							</div>

							<div class="col-md-6">
							    <div class="form-group">
								   <label class="control-label">{{ _lang('Applied Amount') }}</label>						
								   <input type="text" class="form-control float-field" name="applied_amount" value="{{ $loan->applied_amount }}"  {{ $loan->status == 1 ? 'disabled' : 'required' }}>
							    </div>
							</div>

							<div class="col-md-6">
						        <div class="form-group">
							        <label class="control-label">{{ _lang('Late Payment Penalties') }}</label>		
							        <div class="input-group">
								        <input type="text" class="form-control float-field" name="late_payment_penalties" value="{{ $loan->late_payment_penalties }}" required>
								        <div class="input-group-append">
										    <span class="input-group-text">%</span>
										</div>
									</div>
						        </div>
						    </div>

							<div class="col-md-12">
							    <div class="form-group">
								   <label class="control-label">{{ _lang('Attachment') }}</label>						
								   <input type="file" class="dropify" name="attachment" data-default-file="{{ asset('uploads/media/'.$loan->attachment) }}"  {{ $loan->status == 1 ? 'disabled' : '' }}>
							    </div>
							</div>

							<div class="col-md-12">
							    <div class="form-group">
								   <label class="control-label">{{ _lang('Description') }}</label>						
								   <textarea class="form-control" name="description">{{ $loan->description }}</textarea>
							    </div>
							</div>

							<div class="col-md-12">
							    <div class="form-group">
								   <label class="control-label">{{ _lang('Remarks') }}</label>						
								   <textarea class="form-control" name="remarks">{{ $loan->remarks }}</textarea>
							    </div>
							</div>

								
							<div class="col-md-12">
								<div class="form-group">
									<button type="submit" class="btn btn-primary">{{ _lang('Update Changes') }}</button>
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
	$(document).on('change','#borrower_id',function(){
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


