<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ action('CustomFieldController@update', $id) }}" enctype="multipart/form-data">
	{{ csrf_field()}}
	<input name="_method" type="hidden" value="PATCH">				
	
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
			   <label class="control-label">{{ _lang('Field Name') }}</label>						
			   <input type="text" class="form-control" name="field_name" value="{{ $customfield->field_name }}" required>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label">{{ _lang('Field Type') }}</label>						
				<select class="form-control" name="field_type"  required>
					<option value="textbox" {{ $customfield->field_type == 'textbox' ? 'selected' : '' }}>{{ _lang('Text box') }}</option>
					<option value="selectbox" {{ $customfield->field_type == 'selectbox' ? 'selected' : '' }}>{{ _lang('Select box') }}</option>
				</select>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
			   <label class="control-label">{{ _lang('Default Value').' ('._lang('Comma Seperator for select box option').')' }}</label>						
			   <textarea class="form-control" name="default_valus">{{ $customfield->default_valus }}</textarea>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Required') }}</label>						
				<select class="form-control" name="validation_rules"  required>
					<option value="yes" {{ $customfield->validation_rules == 'yes' ? 'selected' : '' }}>{{ _lang('Yes') }}</option>
					<option value="no" {{ $customfield->validation_rules == 'no' ? 'selected' : '' }}>{{ _lang('No') }}</option>
				</select>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Section') }}</label>						
				<select class="form-control select2" name="section_id" >
					<option value="">{{ _lang('Select One') }}</option>
					{{ create_option('custom_field_sections','id','section_name',$customfield->section_id) }}
				</select>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Status') }}</label>						
				<select class="form-control" name="status"  required>
					<option value="1" {{ $customfield->status == 1 ? 'selected' : '' }}>{{ _lang('Active') }}</option>
					<option value="0"  {{ $customfield->status == 0 ? 'selected' : '' }}>{{ _lang('In Active') }}</option>
				</select>
			</div>
		</div>

		
	    <input type="hidden" name="form_type" value="{{ $customfield->form_type }}">

		
		<div class="form-group">
		    <div class="col-md-12">
			    <button type="submit" class="btn btn-primary">{{ _lang('Update') }}</button>
		    </div>
		</div>
	</div>
</form>

