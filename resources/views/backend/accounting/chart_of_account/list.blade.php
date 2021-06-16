@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="home"></i></div>
				<span>{{ _lang('Income/Expense Category') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title"><span class="panel-title">{{ _lang('Income/Expense Category') }}</span>
						<button class="btn btn-primary btn-sm float-right ajax-modal" data-title="{{ _lang('Add Income/Expense Category') }}" data-href="{{ route('category.create') }}">{{ _lang('Add New') }}</button>
					</h4>
					<table id="chart_of_accounts_table" class="table table-bordered data-table">
						<thead>
						    <tr>
							    <th>{{ _lang('Name') }}</th>
								<th>{{ _lang('Type') }}</th>
								<th class="text-center">{{ _lang('Action') }}</th>
						    </tr>
						</thead>
						<tbody>
						    @foreach($chartofaccounts as $chartofaccount)
						    <tr id="row_{{ $chartofaccount->id }}">
								<td class='name'>{{ $chartofaccount->name }}</td>
								<td class='type'>{{ ucwords($chartofaccount->type) }}</td>
								
								<td class="text-center">
									<div class="dropdown">
									  <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									  {{ _lang('Action') }}
									  </button>
									  <form action="{{ action('ChartOfAccountController@destroy', $chartofaccount['id']) }}" method="post">
										{{ csrf_field() }}
										<input name="_method" type="hidden" value="DELETE">
										
										<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
											<a href="{{ action('ChartOfAccountController@edit', $chartofaccount['id']) }}" data-title="{{ _lang('Update Income/Expense Category') }}" class="dropdown-item ajax-modal"><i class="mdi mdi-pencil"></i> {{ _lang('Edit') }}</a>
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
