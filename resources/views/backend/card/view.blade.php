@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="eye"></i></div>
				<span>{{ _lang('View Card Details') }}</span>
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
					  <h4 class="card-title">{{ _lang('View Card Details') }}</h4>
					  <table class="table table-bordered">
							<tr><td>{{ _lang('Account Owner') }}</td><td>{{ $card->owner->first_name.' '.$card->owner->last_name }}</td></tr>
							<tr><td>{{ _lang('Card Number') }}</td><td>{{ $card->card_number }}</td></tr>
							<tr><td>{{ _lang('Card Type') }}</td><td>{{ $card->card_type->card_type .' (' .$card->card_type->currency->name .')' }}</td></tr>
							<tr><td>{{ _lang('Status') }}</td><td>{{ $card->status == 1 ? _lang('Active') : _lang('Blocked') }}</td></tr>
							<tr><td>{{ _lang('Expiration Date') }}</td><td>{{ $card->expiration_date }}</td></tr>
							<tr><td>{{ _lang('CVV') }}</td><td>{{ $card->cvv }}</td></tr>
							<tr><td>{{ _lang('Note') }}</td><td>{{ $card->note }}</td></tr>
							@if(Auth::user()->user_type == 'admin')
								<tr><td>{{ _lang('Created By') }}</td><td>{{ $card->created_user->first_name .' ('. $card->created_at .')' }}</td></tr>
								<tr><td>{{ _lang('Updated By') }}</td><td>{{ $card->updated_user->first_name .' ('. $card->updated_at .')'  }}</td></tr>
							@endif
						</table>
					</div>
				</div>
		    </div>
		</div>
	</div>
</div>
@endsection


