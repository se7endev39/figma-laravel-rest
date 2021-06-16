@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="trending-up"></i></div>
				<span>{{ _lang('Referral Commissions') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">

				<span class="d-none panel-title">{{ _lang('Referral Commissions') }}</span>
				<div class="card-body">
				   <table class="table table-striped">
						<thead>
							<th>{{ _lang('Amount') }}</th>
							<th>{{ _lang('Credit Account') }}</th>
							<th>{{ _lang('Action') }}</th>
						</thead>
						<tbody>
							@if( count($referral_commissions) == 0 )
                                <tr><td colspan="3" class="text-center">{{ _lang('No Commission Available !') }}</td></tr>
							@endif

						    @foreach($referral_commissions as $commission)
						    <form action="{{ url('user/transfer_referral_commissions') }}" method="post">
								@csrf
								<input type="hidden" name="currency_id" value="{{ $commission->currency_id }}"/>
								<tr>
								   <td>{{ $commission->currency->name.' '.decimalPlace($commission->amount) }}</td>							 
								   <td>  
									   <select name="account_id" required>
									      @foreach(\App\Account::where('user_id',Auth::id())->where('status',1)->get() as $credit_account )
											  <option value="{{ $credit_account->id }}">{{ $credit_account->account_number.' - '.$credit_account->account_type->account_type.' ('.$credit_account->account_type->currency->name.')' }}</option>
										  @endforeach
									   </select>
								   </td>
								   <td><button type="submit" class="btn btn-primary btn-sm">{{ _lang('Add To Account') }}</button></td>							 
								</tr>
							</form>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>	
	</div>
</div>

@endsection

@section('js-script')
<script>
	document.title = $(".panel-title").html();
</script>
@endsection


