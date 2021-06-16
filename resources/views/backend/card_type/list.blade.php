@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="credit-card"></i></div>
				<span>{{ _lang('Card Types') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card no-export">
				<div class="card-body">
				 <h4 class="card-title"><span class="panel-title">{{ _lang('Card Types') }}</span>
					<button class="btn btn-primary btn-sm float-right ajax-modal" data-title="{{ _lang('Add Card Type') }}" data-href="{{ route('card_types.create') }}">{{ _lang('Add New') }}</button>
				 </h4>
				 <table class="table table-bordered data-table">
					<thead>
					  <tr>
						<th>{{ _lang('Card Type') }}</th>
						<th>{{ _lang('Currency') }}</th>
						<th class="text-center">{{ _lang('Action') }}</th>
					  </tr>
					</thead>
					<tbody>
					  
					  @foreach($cardtypes as $cardtype)
					  <tr id="row_{{ $cardtype->id }}">
						<td class='card_type'>{{ $cardtype->card_type }}</td>
						<td class='currency_id'>{{ $cardtype->currency->name }}</td>
						<td class="text-center">
							<div class="dropdown">
							  <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							  {{ _lang('Action') }}
							  </button>
							  <form action="{{ action('CardTypeController@destroy', $cardtype['id']) }}" method="post">
								{{ csrf_field() }}
								<input name="_method" type="hidden" value="DELETE">
								
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
									<button data-href="{{ action('CardTypeController@edit', $cardtype['id']) }}" data-title="{{ _lang('Update Card Type') }}" class="dropdown-item dropdown-edit ajax-modal"><i class="mdi mdi-pencil"></i> {{ _lang('Edit') }}</button>
									<button data-href="{{ action('CardTypeController@show', $cardtype['id']) }}" data-title="{{ _lang('View Card Type') }}" class="dropdown-item dropdown-view ajax-modal"><i class="mdi mdi-eye"></i> {{ _lang('View') }}</button>
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


