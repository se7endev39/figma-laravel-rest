@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="map"></i></div>
				<span>{{ _lang('Languages') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
				
					<h4 class="card-title">
						<span class="panel-title">{{ _lang('Languages') }}</span>
						<a class="btn btn-primary btn-sm float-right" href="{{ route('languages.create') }}">{{ _lang('Add New') }}</a>
					</h4>
				 
					<table class="table table-striped data-table">
						<thead>
						  <tr>
							<th>{{ _lang('Language Name') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
						  </tr>
						</thead>
						<tbody>  
						  @foreach(get_language_list() as $language)
						  <tr>
							<td>{{ $language }}</td>	
							<td class="text-center">
								<div class="dropdown">
								  <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								  {{ _lang('Action') }}
								  </button>
								  <form action="{{ action('LanguageController@destroy', $language) }}" method="post">
									{{ csrf_field() }}
									<input name="_method" type="hidden" value="DELETE">
									
									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<a href="{{ action('LanguageController@edit', $language) }}" class="dropdown-item"><i class="mdi mdi-pencil"></i> {{ _lang('Edit') }}</a>
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


