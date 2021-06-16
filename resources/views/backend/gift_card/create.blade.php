@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="plus-circle"></i></div>
				<span>{{ _lang('Create Gift Card') }}</span>
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
					  <h4 class="card-title panel-title">{{ _lang('Create Gift Card') }}</h4>
					  <form method="post" class="validate" autocomplete="off" action="{{ route('gift_cards.store') }}" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="row">
							<div class="col-md-6">
							    @if(Auth::user()->user_type == 'user')
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Debit Account') }}</label>						
										<select class="form-control select2" name="debit_account" required>
											@foreach(\App\Account::where('user_id',Auth::id())->where('status',1)->get() as $debit_account )
												<option value="{{ $debit_account->id }}">{{ $debit_account->account_number.' - '.$debit_account->account_type->account_type.' ('.$debit_account->account_type->currency->name.')' }}</option>
											@endforeach
										</select>
									</div>
								</div>
								@endif
								
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">{{ _lang('Currency') }}</label>						
										<select class="form-control select2" name="currency_id" required>
											 <option value="">{{ _lang('Select Currency') }}</option>
											 {{ create_option('currency', 'id', 'name', old('currency_id'), array('status =' => 1)) }}
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
									<label class="control-label">{{ _lang('Code') }}</label>						
									<input type="text" class="form-control" name="code" value="{{ generate_gift_card() }}" readonly="true" required>
								  </div>
								</div>

							
								<div class="col-md-12">
								  <div class="form-group">
									<button type="submit" class="btn btn-primary">{{ _lang('Create Gift Card') }}</button>
								  </div>
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


