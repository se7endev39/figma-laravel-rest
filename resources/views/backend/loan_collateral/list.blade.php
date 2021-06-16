@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="align-left"></i></div>
				<span>{{ _lang('Collateral List') }}</span>
			</h1>
		</div>
	</div>
</div>


<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title"><span class="panel-title">{{ _lang('Collateral List') }}</span>
						<a class="btn btn-primary btn-sm float-right" href="{{ route('loan_collaterals.create',['loan_id' => $loan_id]) }}">{{ _lang('Add New') }}</a>
					</h4>
					<table id="loan_collaterals_table" class="table table-bordered data-table">
						<thead>
						    <tr>
							    <th>{{ _lang('Loan ID') }}</th>
								<th>{{ _lang('Name') }}</th>
								<th>{{ _lang('Collateral Type') }}</th>
								<th>{{ _lang('Serial Number') }}</th>
								<th>{{ _lang('Estimated Price') }}</th>
								<th class="text-center">{{ _lang('Action') }}</th>
						    </tr>
						</thead>
						<tbody>
						    @foreach($loancollaterals as $loancollateral)
						    <tr data-id="row_{{ $loancollateral->id }}">
								<td class='loan_id'>{{ $loancollateral->loan_id }}</td>
								<td class='name'>{{ $loancollateral->name }}</td>
								<td class='collateral_type'>{{ $loancollateral->collateral_type }}</td>
								<td class='serial_number'>{{ $loancollateral->serial_number }}</td>
								<td class='estimated_price'>{{ $loancollateral->estimated_price }}</td>		
								<td class="text-center">
									<div class="dropdown">
									  <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									  {{ _lang('Action') }}
									  </button>
									  <form action="{{ action('LoanCollateralController@destroy', $loancollateral['id']) }}" method="post">
										{{ csrf_field() }}
										<input name="_method" type="hidden" value="DELETE">
										
										<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
											<a href="{{ action('LoanCollateralController@edit', $loancollateral['id']) }}" class="dropdown-item dropdown-edit dropdown-edit"><i class="mdi mdi-pencil"></i> {{ _lang('Edit') }}</a>
											<a href="{{ action('LoanCollateralController@show', $loancollateral['id']) }}" class="dropdown-item dropdown-view dropdown-view"><i class="mdi mdi-eye"></i> {{ _lang('View') }}</a>
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