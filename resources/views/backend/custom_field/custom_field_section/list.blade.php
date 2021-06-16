@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="align-center"></i></div>
				<span>{{ _lang('Section List') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title"><span class="panel-title">{{ _lang('Section List') }}</span>
						<button class="btn btn-primary btn-sm float-right ajax-modal" data-title="{{ _lang('Add New Section') }}" data-href="{{route('custom_field_sections.create')}}">{{ _lang('Add New') }}</button>
					</h4>
					<table id="custom_field_sections_table" class="table">
						<thead>
						    <tr>
							    <th>{{ _lang('Section Name') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
						    </tr>
						</thead>
						<tbody>
						    @foreach($cfsections as $cfsection)
						    <tr id="row_{{ $cfsection->id }}">
								<td class='section_name'>{{ $cfsection->section_name }}</td>
								
								<td class="text-center">
									<div class="dropdown">
									  <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									  {{ _lang('Action') }}
									  </button>
									  <form action="{{ action('CFSectionController@destroy', $cfsection['id']) }}" method="post">
										{{ csrf_field() }}
										<input name="_method" type="hidden" value="DELETE">
										
										<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
											<button data-href="{{ action('CFSectionController@edit', $cfsection['id']) }}" data-title="{{ _lang('Update Section Details') }}" class="dropdown-item ajax-modal"><i class="mdi mdi-pencil"></i> {{ _lang('Edit') }}</button>
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
