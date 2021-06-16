@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="card">
		    <div class="card-header">
				<span class="panel-title">{{ _lang('View Expense Details') }}</span>
			</div>
			
			<div class="card-body">
			    <table class="table table-bordered">
					<tr><td>{{ _lang('Date') }}</td><td>{{ $financetransaction->trans_date }}</td></tr>
					<tr>
						<td>{{ _lang('Expense Category') }}</td>
						<td>{{ $financetransaction->category->name }}</td>
					</tr>
					<tr><td>{{ _lang('Amount') }}</td><td>{{ get_base_currency().' '.$financetransaction->amount }}</td></tr>
					<tr><td>{{ _lang('Reference') }}</td><td>{{ $financetransaction->reference }}</td></tr>
					<tr><td>{{ _lang('Note') }}</td><td>{{ $financetransaction->note }}</td></tr>
					<tr>
						<td>{{ _lang('Attachment') }}</td>
						<td>
							{!! $financetransaction->attachment == "" ? '' : '<a href="'. asset('uploads/transactions/'.$financetransaction->attachment) .'" target="_blank">'._lang('Download').'</a>' !!}
						</td>
					</tr>
					
				</table>
			</div>
	    </div>
	</div>
</div>
@endsection


