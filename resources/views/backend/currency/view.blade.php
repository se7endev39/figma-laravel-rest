@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="eye"></i></div>
				<span>{{ _lang('View Currency') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title panel-title">{{ _lang('View Currency') }}</h4>

					<table class="table table-bordered">
						<tr><td>{{ _lang('Name') }}</td><td>{{ $currency->name }}</td></tr>
						<tr><td>{{ _lang('Base Currency') }}</td><td>{{ $currency->base_currency == '1' ? _lang('Yes') : _lang('No') }}</td></tr>
						<tr><td>{{ _lang('Exchange Rate') }}</td><td>{{ $currency->exchange_rate }}</td></tr>
						<tr><td>{{ _lang('Status') }}</td><td>{{ $currency->status == '1' ? _lang('Active') : _lang('In-Active') }}</td></tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection


