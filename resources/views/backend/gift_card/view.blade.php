@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
		    <div class="card-header">
				<h4 class="card-title">{{ _lang('View Gift Card') }}</h4>
			</div>
			
			<div class="card-content">
				<div class="card-body">
				  <table class="table table-bordered">
					<tr><td>{{ _lang('Currency') }}</td><td>{{ $giftcard->currency->name }}</td></tr>
					<tr><td>{{ _lang('Amount') }}</td><td>{{ decimalPlace($giftcard->amount) }}</td></tr>
					<tr><td>{{ _lang('Code') }}</td><td>{{ $giftcard->code }}</td></tr>
					<tr><td>{{ _lang('Status') }}</td><td>{{ $giftcard->status == 1 ? _lang('Unused') : _lang('Used') }}</td></tr>
					<tr><td>{{ _lang('Redeem By') }}</td><td>{{ $giftcard->redeem->first_name.' '.$giftcard->redeem->last_name }}</td></tr>
					<tr><td>{{ _lang('Redeem Date') }}</td><td>{{ $giftcard->redeem_date != '' ? date('d M, Y',strtotime($giftcard->redeem_date)) : '' }}</td></tr>
				  </table>
				</div>
			</div>
	    </div>
	</div>
</div>
@endsection


