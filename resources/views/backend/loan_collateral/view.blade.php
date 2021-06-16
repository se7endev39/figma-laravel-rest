@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="eye"></i></div>
				<span>{{ _lang('View Collateral Details') }}</span>
			</h1>
		</div>
	</div>
</div>


<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
			    <div class="card-header">
					<span class="panel-title">{{ _lang('View Collateral Details') }}</span>
				</div>
				
				<div class="card-body">
				    <table class="table table-bordered">
						<tr><td>{{ _lang('Name') }}</td><td>{{ $loancollateral->name }}</td></tr>
						<tr><td>{{ _lang('Collateral Type') }}</td><td>{{ $loancollateral->collateral_type }}</td></tr>
						<tr><td>{{ _lang('Serial Number') }}</td><td>{{ $loancollateral->serial_number }}</td></tr>
						<tr>
							<td>{{ _lang('Estimated Price') }}</td>
							<td>{{ decimalPlace($loancollateral->estimated_price) }}</td>
						</tr>
						<tr>
							<td>{{ _lang('Attachments') }}</td>
							<td>
								{!! $loancollateral->attachments == "" ? '' : '<a href="'. asset('uploads/media/'.$loancollateral->attachments) .'" target="_blank">'._lang('Download').'</a>' !!}
							</td>
						</tr>
						<tr><td>{{ _lang('Description') }}</td><td>{{ $loancollateral->description }}</td></tr>
				    </table>
				</div>
		    </div>
		</div>
	</div>
</div>
@endsection


