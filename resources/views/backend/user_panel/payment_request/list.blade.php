@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="arrow-right-circle"></i></div>
				<span>{{ _lang('All Payment Request') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card no-export">
				<div class="card-body">
				 <h4 class="card-title"><span class="panel-title">{{ _lang('All Payment Request') }}</span>
					<a class="btn btn-primary btn-sm float-right" href="{{ route('payment_requests.create') }}">{{ _lang('Add New') }}</a>
				 </h4>
				 <table class="table table-bordered data-table">
					<thead>
					  <tr>
						<th>{{ _lang('Credit Account') }}</th>
						<th>{{ _lang('Amount') }}</th>
						<th>{{ _lang('Status') }}</th>
						<th>{{ _lang('Description') }}</th>
						<th class="text-center">{{ _lang('Action') }}</th>
					  </tr>
					</thead>
					<tbody>
					  
					  @foreach($paymentrequests as $paymentrequest)
					  <tr id="row_{{ $paymentrequest->id }}">
						<td class='account_id'>{{ $paymentrequest->account->account_number }}</td>
						<td class='amount'>{{ $paymentrequest->account->account_type->currency->name.' '.$paymentrequest->amount }}</td>
						<td class='status'>{{ ucwords($paymentrequest->status) }}</td>
						<td class='description'>{{ $paymentrequest->description }}</td>
						<td class="text-center">
							<div class="dropdown">
							  <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							  {{ _lang('Action') }}
							  </button>
							  <form action="{{ action('PaymentRequestController@destroy', $paymentrequest['id']) }}" method="post">
								{{ csrf_field() }}
								<input name="_method" type="hidden" value="DELETE">
								
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
									<button data-href="{{ action('PaymentRequestController@show', $paymentrequest['id']) }}" data-title="{{ _lang('View Payment Request') }}" class="dropdown-item ajax-modal"><i class="mdi mdi-eye"></i> {{ _lang('View') }}</button>
									<button class="btn-remove dropdown-item" type="submit"><i class="mdi mdi-delete"></i> {{ _lang('Delete') }}</button>
								</div>
							  </form>
							</div>
						</td>
					  </tr>
					  @endforeach
					</tbody>
				  </table>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
