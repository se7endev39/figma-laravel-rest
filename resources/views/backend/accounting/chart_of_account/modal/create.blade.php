<form method="post" class="ajax-submit" autocomplete="off" action="{{ route('category.store') }}" enctype="multipart/form-data">
	{{ csrf_field() }}
	
    <div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Name') }}</label>						
			<input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{{ _lang('Type') }}</label>						
			<select class="form-control" name="type"  required>
				<option value="">{{ _lang('Select One') }}</option>
				<option value="income">{{ _lang('Income') }}</option>
<option value="expense">{{ _lang('Expense') }}</option>
			</select>
		</div>
	</div>

	
	<div class="col-md-12">
	    <div class="form-group">
		    <button type="submit" class="btn btn-primary">{{ _lang('Save') }}</button>
	    </div>
	</div>
</form>
