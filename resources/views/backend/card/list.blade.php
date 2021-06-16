@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="credit-card"></i></div>
				<span>{{ _lang('Card List') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card no-export">
				<div class="card-body">
				 <h4 class="card-title"><span class="panel-title">{{ _lang('Card List') }}</span>
					<button class="btn btn-primary btn-sm float-right ajax-modal" data-title="{{ _lang('Create New Card') }}" data-href="{{ route('cards.create') }}">{{ _lang('Add New') }}</button>
				 </h4>
				 <table class="table table-bordered data-table">
					<thead>
					  <tr>
						<th>{{ _lang('Card Number') }}</th>
						<th>{{ _lang('Card Type') }}</th>
						<th>{{ _lang('Card Owner') }}</th>
						<th>{{ _lang('Status') }}</th>
						<th>{{ _lang('Expire Date') }}</th>
						<th class="text-right">{{ _lang('Balance') }}</th>
						<th class="text-center">{{ _lang('Action') }}</th>
					  </tr>
					</thead>
					<tbody>
					  
					  @foreach($cards as $card)
					  <tr id="row_{{ $card->id }}">
						<td class='card_number'>{{ $card->card_number }}</td>
						<td class='card_type_id'>{{ $card->card_type->card_type.' ('.$card->card_type->currency->name.')' }}</td>
						<td class='user_id'>
						    @if($card->owner->id != '')
								<a href="{{ action('UserController@show', $card->owner->id) }}" class="ajax-modal" data-title="{{ _lang('View User Details') }}">{!! $card->owner->first_name.' '.$card->owner->last_name !!}</a>
						    @endif
						</td>
						<td class='status'>{{ $card->status == 1 ? _lang('Active') : _lang('Blocked') }}</td>
						<td class='expiration_date'>{{ $card->expiration_date }}</td>
						<td class='balance text-right'>{{ decimalPlace($card->balance) }}</td>
						<td class="text-center">
							<div class="dropdown">
							  <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							  {{ _lang('Action') }}
							  </button>
							  <form action="{{ action('CardController@destroy', $card['id']) }}" method="post">
								{{ csrf_field() }}
								<input name="_method" type="hidden" value="DELETE">
								
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
									<button data-href="{{ action('CardController@edit', $card['id']) }}" data-title="{{ _lang('Update Card Details') }}" class="dropdown-item dropdown-edit ajax-modal"><i class="mdi mdi-pencil"></i> {{ _lang('Edit') }}</button>
									<button data-href="{{ action('CardController@show', $card['id']) }}" data-title="{{ _lang('View Card Details') }}" class="dropdown-item dropdown-view ajax-modal"><i class="mdi mdi-eye"></i> {{ _lang('View') }}</button>
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


