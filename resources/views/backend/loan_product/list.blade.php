@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="align-left"></i></div>
				<span>{{ _lang('Loan Product List') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
					<span class="panel-title">{{ _lang('Loan Product List') }}</span>
					<a class="btn btn-primary btn-sm float-right" href="{{ route('loan_products.create') }}">{{ _lang('Add New') }}</a>
				</div>
				<div class="card-body">
					<table id="loan_products_table" class="table table-bordered data-table">
						<thead>
						    <tr>
							    <th>{{ _lang('Name') }}</th>
								<th>{{ _lang('Interest Rate') }}</th>
								<th>{{ _lang('Interest Type') }}</th>
								<th>{{ _lang('Term') }}</th>
								<th>{{ _lang('Term Period') }}</th>
								<th class="text-center">{{ _lang('Action') }}</th>
						    </tr>
						</thead>
						<tbody>
						    @foreach($loanproducts as $loanproduct)
						    <tr data-id="row_{{ $loanproduct->id }}">
								<td class='name'>{{ $loanproduct->name }}</td>
								<td class='interest_rate'>{{ $loanproduct->interest_rate }}</td>
								<td class='interest_type'>{{ ucwords(str_replace("_"," ", $loanproduct->interest_type)) }}</td>
								<td class='term'>{{ $loanproduct->term }}</td>
								<td class='term_period'>
									@if($loanproduct->term_period === '+1 month')
										{{ _lang('Month') }}
									@elseif($loanproduct->term_period === '+1 year')
										{{ _lang('Year') }}
									@elseif($loanproduct->term_period === '+1 day')
										{{ _lang('Day') }}
									@elseif($loanproduct->term_period === '+1 week')
										{{ _lang('Week') }}
									@endif									
								</td>
								
								<td class="text-center">
									<div class="dropdown">
									  <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									  {{ _lang('Action') }}
									  </button>
									  <form action="{{ action('LoanProductController@destroy', $loanproduct['id']) }}" method="post">
										{{ csrf_field() }}
										<input name="_method" type="hidden" value="DELETE">
										
										<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
											<a href="{{ action('LoanProductController@edit', $loanproduct['id']) }}" class="dropdown-item dropdown-edit dropdown-edit"><i class="mdi mdi-pencil"></i> {{ _lang('Edit') }}</a>
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