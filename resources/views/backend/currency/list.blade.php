@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="dollar-sign"></i></div>
				<span>{{ _lang('Currency List') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card no-export">
				<div class="card-body">
					<h4 class="card-title"><span class="panel-title">{{ _lang('Currency List') }}</span>
						<button class="btn btn-primary btn-sm float-right ajax-modal" data-title="{{ _lang('Add Currency') }}" data-href="{{route('currency.create')}}">{{ _lang('Add New') }}</button>
					</h4>
					<table class="table table-bordered data-table">
						<thead>
							<tr>
								<th>{{ _lang('Name') }}</th>
								<th>{{ _lang('Base Currency') }}</th>
								<th>{{ _lang('Exchange Rate') }}</th>
								<th>{{ _lang('Status') }}</th>
								<th class="text-center">{{ _lang('Action') }}</th>
							</tr>
						</thead>
						<tbody>

							@foreach($currencys as $currency)
							<tr id="row_{{ $currency->id }}">
								<td class='name'>{{ $currency->name }}</td>
								<td class='base_currency'>{!! $currency->base_currency == '1' ? status(_lang('Yes'), 'primary') : status(_lang('No'), 'danger') !!}</td>
								<td class='exchange_rate'>{{ $currency->exchange_rate }}</td>
								<td class='status'>{!! $currency->status == '1' ?  status(_lang('Active'), 'success') : status(_lang('In-Active'), 'danger') !!}</td>

								<td class="text-center">
									<div class="dropdown">
										<button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											{{ _lang('Action') }}
										</button>
										<form action="{{ action('CurrencyController@destroy', $currency['id']) }}" method="post">
											{{ csrf_field() }}
											<input name="_method" type="hidden" value="DELETE">

											<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
												<button data-href="{{ action('CurrencyController@edit', $currency['id']) }}" data-title="{{ _lang('Update Currency') }}" class="dropdown-item dropdown-edit ajax-modal"><i class="mdi mdi-pencil"></i> {{ _lang('Edit') }}</button>
												<button data-href="{{ action('CurrencyController@show', $currency['id']) }}" data-title="{{ _lang('View Currency') }}" class="dropdown-item dropdown-view ajax-modal"><i class="mdi mdi-eye"></i> {{ _lang('View') }}</button>
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


