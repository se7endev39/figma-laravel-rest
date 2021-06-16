@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="edit"></i></div>
				<span>{{ _lang('Update Card Details') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">	
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-content">
					<div class="card-body">
						<h4 class="card-title">{{ _lang('Update Card Details') }}</h4>
						<form method="post" class="validate" autocomplete="off" action="{{ action('CardController@update', $id) }}" enctype="multipart/form-data">
							{{ csrf_field()}}
							<input name="_method" type="hidden" value="PATCH">				
							<div class="col-md-6">
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
								
								<div class="col-md-12">
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

								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Status') }}</label>						
										<select class="form-control" name="status" required>
											 <option value="1" {{ $card->status == '1' ? 'selected' : '' }}>{{ _lang('Active') }}</option>
											 <option value="0" {{ $card->status == '0' ? 'selected' : '' }}>{{ _lang('Blocked') }}</option>
										</select>	
									</div>
								</div>

								<div class="col-md-12">
									 <div class="form-group">
										<label class="control-label">{{ _lang('Expiration Date') }}</label>						
										<input type="text" class="form-control datepicker" name="expiration_date" value="{{ $card->expiration_date }}" required>
									 </div>
								</div>

								<div class="col-md-12">
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
					
								<div class="col-md-12">
								  <div class="form-group">
									<button type="submit" class="btn btn-primary">{{ _lang('Update') }}</button>
								  </div>
								</div>
							</div>	
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection


