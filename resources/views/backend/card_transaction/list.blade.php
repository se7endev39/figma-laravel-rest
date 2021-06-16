@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="credit-card"></i></div>
				<span>{{ _lang('Card Transactions') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card no-export">
				<div class="card-body">
				 <h4 class="card-title"><span class="panel-title">{{ _lang('Card Transactions') }}</span>
					<button class="btn btn-primary btn-sm float-right ajax-modal" data-title="{{ _lang('New Card Transaction') }}" data-href="{{ route('card_transactions.create') }}">{{ _lang('Add New') }}</button>
				 </h4>
				 <table class="table table-bordered data-table">
					<thead>
					  <tr>
						<th>{{ _lang('Card Number') }}</th>
						<th>{{ _lang('DR/CR') }}</th>
						<th class="text-center">{{ _lang('Status') }}</th>
						<th class="text-right">{{ _lang('Amount') }}</th>
						<th class="text-center">{{ _lang('Action') }}</th>
					  </tr>
					</thead>
					<tbody>
					  
					  @foreach($cardtransactions as $cardtransaction)
					  <tr id="row_{{ $cardtransaction->id }}">
						<td class='card_id'>{{ $cardtransaction->card->card_number.' - '.$cardtransaction->card->card_type->card_type }}</td>
						<td class='dr_cr'>{{ $cardtransaction->dr_cr == 'dr' ? _lang('Debit') : _lang('Credit') }}</td>
						<td class="status text-center">
						    @if($cardtransaction->status == 0)
								<span class="badge badge-warning">{{ _lang('Pending') }}</span>
							@elseif($cardtransaction->status == 1)
								<span class="badge badge-success">{{ _lang('Completed') }}</span>
							@elseif($cardtransaction->status == 2)
								<span class="badge badge-danger">{{ _lang('Rejected') }}</span>
							@endif
						</td>
						<td class='amount text-right'>{{ $cardtransaction->card->card_type->currency->name.' '. decimalPlace($cardtransaction->amount) }}</td>
						<td class="text-center">
							<div class="dropdown">
							  <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							  {{ _lang('Action') }}
							  </button>
							  <form action="{{ action('CardTransactionController@destroy', $cardtransaction['id']) }}" method="post">
								{{ csrf_field() }}
								<input name="_method" type="hidden" value="DELETE">
								
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
									<button data-href="{{ action('CardTransactionController@edit', $cardtransaction['id']) }}" data-title="{{ _lang('Update Card Transaction') }}" class="dropdown-item dropdown-edit ajax-modal"><i class="mdi mdi-pencil"></i> {{ _lang('Edit') }}</button>
									<button data-href="{{ action('CardTransactionController@show', $cardtransaction['id']) }}" data-title="{{ _lang('View Card Transaction') }}" class="dropdown-item dropdown-view ajax-modal"><i class="mdi mdi-eye"></i> {{ _lang('View') }}</button>
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


