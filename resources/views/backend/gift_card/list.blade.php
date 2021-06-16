@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="gift"></i></div>
				<span>{{ $title }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card no-export">
				<div class="card-body">
				 <h4 class="card-title"><span class="panel-title">{{ $title }}</span>
					<a class="btn btn-primary btn-sm float-right" href="{{ route('gift_cards.create') }}">{{ _lang('Add New') }}</a>
				 </h4>
				 <table class="table table-bordered data-table">
					<thead>
					  <tr>
						<th>{{ _lang('Currency') }}</th>
						<th class='text-right'>{{ _lang('Amount') }}</th>
						<th>{{ _lang('Status') }}</th>
						<th>{{ _lang('Redeem By') }}</th>
						<th class="text-center">{{ _lang('Action') }}</th>
					  </tr>
					</thead>
					<tbody>
					  
					  @foreach($giftcards as $giftcard)
					  <tr id="row_{{ $giftcard->id }}">
						<td class='currency_id'>{{ $giftcard->currency->name }}</td>
						<td class='amount text-right'>{{ decimalPlace($giftcard->amount) }}</td>
						<td class='status'>{!! $giftcard->status == 1 ? status(_lang('Unused'), 'success') : status(_lang('Used'), 'danger') !!}</td>
						<td class='redeem_by'>{{ $giftcard->redeem->first_name.' '.$giftcard->redeem->last_name }}</td>
						
						<td class="text-center">
							<div class="dropdown">
							  <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							  {{ _lang('Action') }}
							  </button>
							  @if($giftcard->status == 1)
								  <form action="{{ action('GiftCardController@destroy', $giftcard['id']) }}" method="post">
									{{ csrf_field() }}
									<input name="_method" type="hidden" value="DELETE">
									
									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<button data-href="{{ action('GiftCardController@show', $giftcard['id']) }}" data-title="{{ _lang('View Gift Card') }}" class="dropdown-item dropdown-view ajax-modal"><i class="mdi mdi-eye"></i> {{ _lang('View') }}</button>
										<button class="btn-remove dropdown-item" type="submit"><i class="mdi mdi-delete"></i> {{ _lang('Delete') }}</button>
									</div>
								  </form>
							  @else
								  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
									<button data-href="{{ action('GiftCardController@show', $giftcard['id']) }}" data-title="{{ _lang('View Gift Card') }}" class="dropdown-item ajax-modal"><i class="mdi mdi-eye"></i> {{ _lang('View') }}</button>
								  </div>
							  @endif
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


