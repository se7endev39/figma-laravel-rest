@extends('layouts.app')

@section('content')

<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="edit-3"></i></div>
				<span>{{ _lang('Custom Fields') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">

				<div class="card-body">
					<h4 class="card-title"><span class="panel-title">{{ _lang('Custom Fields') }}</span>
						<button class="btn btn-primary btn-sm float-right ajax-modal" data-title="{{ _lang('Add New Field') }}" data-href="{{ route('custom_fields.create') }}">{{ _lang('Add New') }}</button>
					</h4>
					<table id="custom_fields_table" class="table table-bordered data-table">
						<thead>
						    <tr>
							    <th>{{ _lang('Field Name') }}</th>
								<th>{{ _lang('Field Type') }}</th>
								<th>{{ _lang('Required') }}</th>
								<th>{{ _lang('Status') }}</th>
								<th class="text-center">{{ _lang('Action') }}</th>
						    </tr>
						</thead>
						<tbody>
						    @foreach($customfields as $customfield)
						    <tr id="row_{{ $customfield->id }}">
								<td class='field_name'>{{ $customfield->field_name }}</td>
								<td class='field_type'>{{ ucwords($customfield->field_type) }}</td>
								<td class='validation_rules'>{{ ucwords($customfield->validation_rules) }}</td>
								<td class='status'>
									{{ $customfield->status == 1 ? _lang('Active') : _lang('In Active') }}
								</td>
								<td class="text-center">
									<div class="dropdown">
									  <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									  {{ _lang('Action') }}
									  </button>
									  <form action="{{ action('CustomFieldController@destroy', $customfield['id']) }}" method="post">
										{{ csrf_field() }}
										<input name="_method" type="hidden" value="DELETE">
										
										<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
											<button data-href="{{ action('CustomFieldController@edit', $customfield['id']) }}" data-title="{{ _lang('Update Custom Field') }}" class="dropdown-item ajax-modal"><i class="mdi mdi-pencil"></i> {{ _lang('Edit') }}</button>
											<button data-href="{{ action('CustomFieldController@show', $customfield['id']) }}" data-title="{{ _lang('View Custom Field') }}" class="dropdown-item ajax-modal"><i class="mdi mdi-eye"></i> {{ _lang('View') }}</button>
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