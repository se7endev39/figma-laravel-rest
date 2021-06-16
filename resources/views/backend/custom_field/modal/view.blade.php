<table class="table table-bordered">
	<tr><td>{{ _lang('Field Name') }}</td><td>{{ $customfield->field_name }}</td></tr>
	<tr><td>{{ _lang('Field Type') }}</td><td>{{ ucwords($customfield->field_type) }}</td></tr>
	<tr><td>{{ _lang('Default Valus') }}</td><td>{{ $customfield->default_valus }}</td></tr>
	<tr><td>{{ _lang('Required') }}</td><td>{{ ucwords($customfield->validation_rules) }}</td></tr>
	<tr><td>{{ _lang('Section') }}</td><td>{{ $customfield->section->section_name }}</td></tr>
	<tr>
		<td>{{ _lang('Status') }}</td>
		<td>{{ $customfield->status == 1 ? _lang('Active') : _lang('In Active') }}</td>
	</tr>
</table>

