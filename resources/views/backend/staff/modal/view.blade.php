<div class="panel panel-default">
<div class="panel-body">
    <table class="table table-bordered">
		<tr><td colspan="2" class="text-center"><img class="img-lg thumbnail" src="{{ $user->profile_picture != "" ? asset('uploads/profile/'.$user->profile_picture) : asset('images/avatar.png') }}"></td></tr>
		<tr><td>{{ _lang('First Name') }}</td><td>{{ $user->first_name }}</td></tr>
		<tr><td>{{ _lang('Last Name') }}</td><td>{{ $user->last_name }}</td></tr>
		<tr><td>{{ _lang('Email') }}</td><td>{{ $user->email }}</td></tr>
		<tr><td>{{ _lang('Phone') }}</td><td>{{ $user->phone }}</td></tr>	
		<tr><td>{{ _lang('User Type') }}</td><td>{{ ucwords($user->user_type) }}</td></tr>	
    </table>
</div>
</div>
