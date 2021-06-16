@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="arrow-right-circle"></i></div>
				<span>{{ _lang('Card Funding Transfer') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">

			@if(Session::has('success'))
				<div class="alert alert-success">
				   <button type="button" class="close" data-dismiss="alert">&times;</button>
	               <strong>{{ session('success') }}</strong>
				</div>	
			@endif

			<div class="card">
				<div class="card-body">
					<h4 class="card-title panel-title">{{ _lang('Card Funding Transfer') }}</h4>
					<form method="post" class="validate" autocomplete="off" action="{{ url('user/card_funding_transfer') }}">
						{{ csrf_field() }}
						<div class="row">
							<div class="col-md-6">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Debit Account') }}</label>						
										<select class="form-control select2" name="debit_account" required>
											@foreach(\App\Account::where('user_id',Auth::id())->get() as $debit_account )
												<option value="{{ $debit_account->id }}">{{ $debit_account->account_number.' - '.$debit_account->account_type->account_type.' ('.$debit_account->account_type->currency->name.')' }}</option>
											@endforeach
										</select>
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Card Number') }}</label>						
										<select class="form-control select2" name="card" required>
											<option value="">{{ _lang('SELECT YOUR CARD') }}</option>
											@foreach(\App\Card::where('user_id',Auth::id())->where('status',1)->get() as $card )
												<option value="{{ $card->id }}">{{ $card->card_number.' - '.$card->card_type->card_type.' ('.$card->card_type->currency->name.')' }}</option>
											@endforeach
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
										<label class="control-label">{{ _lang('Note') }}</label>						
										<textarea class="form-control" name="note">{{ old('note') }}</textarea>
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<button type="submit" class="btn btn-primary">{{ _lang('Make Transfer') }}</button>
									</div>
								</div>
							</div>

                            <div class="col-md-6">
                            	<div class="tips-container">
                            		<p>{{ _lang('Currency will be convert automatically from debit account to credit account') }}</p>
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

