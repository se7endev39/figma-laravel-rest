@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
	<div class="container-fluid">
		<div class="sb-page-header-content py-5">
			<h1 class="sb-page-header-title">
				<div class="sb-page-header-icon"><i data-feather="home"></i></div>
				<span>{{ _lang('Account List') }}</span>
			</h1>
		</div>
	</div>
</div>

<div class="container-fluid mt-n10">
	<div class="row">
		<div class="col-12">
			<div class="card no-export">
				<div class="card-body">
				 <h4 class="card-title"><span class="panel-title">{{ _lang('Account List') }}</span>
					<button class="btn btn-primary btn-sm float-right ajax-modal" data-title="{{ _lang('Create Account') }}" data-href="{{route('accounts.create')}}">{{ _lang('Add New') }}</button>
				 </h4>
				 <table class="table table-bordered data-table">
					<thead>
					  <tr>
						<th>{{ _lang('Account Number') }}</th>
						<th>{{ _lang('Account Owner') }}</th>
						<th>{{ _lang('Account Type') }}</th>
						<th>{{ _lang('Status') }}</th>
						<th class="text-right">{{ _lang('Opening Balance') }}</th>
						<th class="text-right">{{ _lang('Current Balance') }}</th>
						<th class="text-center">{{ _lang('Action') }}</th>
					  </tr>
					</thead>
					<tbody>
					  
					  @foreach($accounts as $account)
					  <tr id="row_{{ $account->id }}">
						<td class='account_number'>{{ $account->account_number }}</td>
						<td class='user_id'>
							@if($account->owner->id != '')
							<a href="{{ action('UserController@show', $account->owner->id) }}" class="ajax-modal" data-title="{{ _lang('View User Details') }}">{!! $account->owner->first_name.' '.$account->owner->last_name !!}</a>
						    @endif
						</td>
						<td class='account_type_id'>{{ $account->account_type->account_type.' ('.$account->account_type->currency->name.')' }}</td>
						<td class='status'>{{ $account->status == 1 ? _lang('Active') : _lang('Blocked') }}</td>
						<td class='opening_balance text-right'>{{ decimalPlace($account->opening_balance) }}</td>				
						<td class='current_balance text-right'>{{ decimalPlace($account->balance) }}</td>					
						<td class="text-center">
							<div class="dropdown">
							  <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							  {{ _lang('Action') }}
							  </button>
							  <form action="{{ action('AccountController@destroy', $account['id']) }}" method="post">
								{{ csrf_field() }}
								<input name="_method" type="hidden" value="DELETE">
								
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
									<button data-href="{{ action('AccountController@edit', $account['id']) }}" data-title="{{ _lang('Update Account') }}" class="dropdown-item dropdown-edit ajax-modal"><i class="mdi mdi-pencil"></i> {{ _lang('Edit') }}</button>
									<button data-href="{{ action('AccountController@show', $account['id']) }}" data-title="{{ _lang('View Account') }}" class="dropdown-item dropdown-view ajax-modal"><i class="mdi mdi-eye"></i> {{ _lang('View') }}</button>
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


