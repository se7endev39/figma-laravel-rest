@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="home"></i></div>
				<span>{{ _lang('Account Types') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card no-export">
				<div class="card-body">
					<h4 class="card-title"><span class="panel-title">{{ _lang('Account Types') }}</span>
						<a class="btn btn-primary btn-sm float-right" href="{{ route('account_types.create') }}">{{ _lang('Add New') }}</a>
					</h4>
					<table class="table table-bordered data-table">
						<thead>
							<tr>
								<th>{{ _lang('Account Type') }}</th>
								<th>{{ _lang('Currency') }}</th>
								<th>{{ _lang('Maintenance Fee (Annually)') }}</th>
								<th>{{ _lang('Interest Rate') }}</th>
								<th>{{ _lang('Auto Create') }}</th>
								<th class="text-center">{{ _lang('Action') }}</th>
							</tr>
						</thead>
						<tbody>

							@foreach($accounttypes as $accounttype)
							<tr id="row_{{ $accounttype->id }}">
								<td class='account_type'>{{ $accounttype->account_type }}</td>
								<td class='currency_id'>{{ $accounttype->currency->name }}</td>
								<td class='maintenance_fee'>{{ decimalPlace($accounttype->maintenance_fee) }}</td>
								<td class='interest_rate'>{{ decimalPlace($accounttype->interest_rate) }}</td>
								<td class='auto_create'>{{ $accounttype->auto_create == 1 ? _lang('Yes') : _lang('No') }}</td>

								<td class="text-center">
									<div class="dropdown">
										<button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											{{ _lang('Action') }}
										</button>
										<form action="{{ action('AccountTypeController@destroy', $accounttype['id']) }}" method="post">
											{{ csrf_field() }}
											<input name="_method" type="hidden" value="DELETE">

											<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
												<a href="{{ action('AccountTypeController@edit', $accounttype['id']) }}" class="dropdown-item dropdown-edit"><i class="mdi mdi-pencil"></i> {{ _lang('Edit') }}</a>
												<button data-href="{{ action('AccountTypeController@show', $accounttype['id']) }}" data-title="{{ _lang('View Account Type') }}" class="dropdown-item dropdown-view ajax-modal"><i class="mdi mdi-eye"></i> {{ _lang('View') }}</button>
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


