<form method="post" class="ajax-screen-submit" autocomplete="off" action="{{ route('custom_fields.store') }}" enctype="multipart/form-data">
	{{ csrf_field() }}
	
	<div class="row">
	    <div class="col-md-6">
			<div class="form-group">
				<label class="control-label">{{ _lang('Field Name') }}</label>						
				<input type="text" class="form-control" name="field_name" value="{{ old('field_name') }}" required>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label">{{ _lang('Field Type') }}</label>						
				<select class="form-control" name="field_type"  required>
					<option value="textbox">{{ _lang('Text box') }}</option>
					<option value="selectbox">{{ _lang('Select box') }}</option>
				</select>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Default Value').' ('._lang('Comma Seperator for select box option').')' }}</label>						
				<textarea class="form-control" name="default_valus">{{ old('default_valus') }}</textarea>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Required') }}</label>						
				<select class="form-control" name="validation_rules"  required>
					<option value="yes">{{ _lang('Yes') }}</option>
					<option value="no">{{ _lang('No') }}</option>
				</select>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Section') }}</label>						
				<select class="form-control select2" name="section_id" >
					<option value="">{{ _lang('Select One') }}</option>
					{{ create_option('custom_field_sections','id','section_name',old('section_id')) }}
				</select>
			</div>
		</div>

		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label">{{ _lang('Status') }}</label>						
				<select class="form-control" name="status"  required>
					<option value="1">{{ _lang('Active') }}</option>
					<option value="0">{{ _lang('In Active') }}</option>
				</select>
			</div>
		</div>

		<input type="hidden" name="form_type" value="signup">

		
		<div class="col-md-12">
		    <div class="form-group">
		        <button type="reset" class="btn btn-danger">{{ _lang('Reset') }}</button>
			    <button type="submit" class="btn btn-primary">{{ _lang('Save') }}</button>
		    </div>
		</div>
	</div>
</form>
