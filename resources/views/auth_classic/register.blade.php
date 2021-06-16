@extends('layouts.login')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-signin my-5 p-3">              

                <div class="card-body">
                    <img class="logo" src="{{ get_logo() }}">
                    <h5 class="text-center py-4">
                        {{ _lang('Create Your Account Now') }}
                    </h5>
                    <form method="POST" class="form-signin validate" autocomplete="off" action="{{ route('register') }}" novalidate>
                        @csrf
							        		
    						<div class="form-group row">
    							<div class="col-md-12">
    								<select class="form-control select2" id="account_type" name="account_type" required>
    								  <option value="">{{ _lang('Select Account Type') }}</option>
    								  <option value="personal" {{ old('account_type') == 'personal' ? 'selected' : '' }}>{{ _lang('Personal') }}</option>
    								  <option value="business" {{ old('account_type') == 'business' ? 'selected' : '' }}>{{ _lang('Business') }}</option>
    								</select>
                                    @if ($errors->has('account_type'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('account_type') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
    						
    						<div class="form-group row {{ old('account_type') == 'business' ? '' : 'd-none' }}" id="business_name">
    						   <div class="col-md-12">
                                    <input type="text" placeholder="{{ _lang('Business Name') }}" class="form-control{{ $errors->has('business_name') ? ' is-invalid' : '' }}" name="business_name" value="{{ old('business_name') }}">

                                    @if ($errors->has('business_name'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('business_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">

    							<div class="col-md-6">
                                    <input id="first_name" type="text" placeholder="{{ _lang('First Name') }}" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name') }}" required autofocus>

                                    @if ($errors->has('first_name'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                    @endif
                                </div>

    							<div class="col-md-6">
                                    <input id="last_name" type="text" placeholder="{{ _lang('Last Name') }}" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}" required autofocus>

                                    @if ($errors->has('last_name'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">

                                <div class="col-md-12">
                                    <input id="email" type="email" placeholder="{{ _lang('E-Mail Address') }}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
    						
    						<div class="form-group row">

                                <div class="col-md-12">
                                    <input type="phone" placeholder="{{ _lang('Mobile') }}" class="form-control phone{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone','+1') }}" required>

                                    @if ($errors->has('phone'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">

                                <div class="col-md-6">
                                    <input id="password" type="password" placeholder="{{ _lang('Password') }}" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            
                               <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" placeholder="{{ _lang('Confirm Password') }}" name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="form-group row">

                                <div class="col-md-6">
                                    <input id="date_of_birth" type="text" placeholder="{{ _lang('Date Of Birth') }}" class="form-control datepicker{{ $errors->has('date_of_birth') ? ' is-invalid' : '' }}" name="date_of_birth" value="{{ old('date_of_birth') }}" required="true" readonly="true">

                                    @if ($errors->has('date_of_birth'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('date_of_birth') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <input id="passport" type="text" placeholder="{{ _lang('Passport') }}" class="form-control{{ $errors->has('passport') ? ' is-invalid' : '' }}" name="passport" value="{{ old('passport') }}" required>

                                    @if ($errors->has('passport'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('passport') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <select id="country_of_residence" class="form-control select2{{ $errors->has('country_of_residence') ? ' is-invalid' : '' }}" name="country_of_residence" required>
                                       <option value="">{{ _lang('Country Of Residence') }}</option>
                                       {{ get_country_list(old('country_of_residence')) }}
                                    </select>

                                    @if ($errors->has('country_of_residence'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('country_of_residence') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <select id="country_of_citizenship" class="form-control select2{{ $errors->has('country_of_citizenship') ? ' is-invalid' : '' }}" name="country_of_citizenship" required>
                                       <option value="">{{ _lang('Country Of Citizenship') }}</option>
                                       {{ get_country_list(old('country_of_citizenship')) }}
                                    </select>

                                    @if ($errors->has('country_of_citizenship'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('country_of_citizenship') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <textarea id="address" type="text" placeholder="{{ _lang('Address') }}" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" required>{{ old('address') }}</textarea>

                                    @if ($errors->has('address'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">

                                <div class="col-md-12">
                                    <input type="text" placeholder="{{ _lang('City') }}" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ old('city') }}" required>

                                    @if ($errors->has('city'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('city') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">

                                <div class="col-md-12">
                                    <input type="text" placeholder="{{ _lang('State') }}" class="form-control{{ $errors->has('state') ? ' is-invalid' : '' }}" name="state" value="{{ old('state') }}" required>

                                    @if ($errors->has('state'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('state') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">

                                <div class="col-md-12">
                                    <input type="text" placeholder="{{ _lang('Zip') }}" class="form-control{{ $errors->has('zip') ? ' is-invalid' : '' }}" name="zip" value="{{ old('zip') }}" required>

                                    @if ($errors->has('zip'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('zip') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
    						
    						<input type="hidden" name="ref" value="{{ isset($_GET['ref']) ? $_GET['ref'] : '' }}"/>


                            @foreach(generate_custom_fields() as $section_id => $section)
                                
                                @if($section_id != 0)
                                        
                                    <h5 class="text-center border p-3 mb-3">
                                        <a class="card-link" data-toggle="collapse" href="#collapse-{{ $section_id }}">
                                        {{ $section[0]->section->section_name }}
                                        </a>
                                    </h5>
                                    
                                    <div id="collapse-{{ $section_id }}" class="collapse">
                                @endif
                                
                                @foreach($section as $cf)
                                    <div class="form-group row">

                                        <div class="col-md-12">

                                            @php $name = str_replace(" ","_",$cf->field_name); @endphp

                                            @if($cf->field_type == 'textbox')

                                                <input type="text" 
                                                placeholder="{{ $cf->field_name }}" 
                                                class="form-control{{ $errors->has('custom_field.'.$name) ? ' is-invalid' : '' }}" 
                                                name="custom_field[{{ $name }}]" 
                                                value="{{ old($name) }}" {{ $cf->validation_rules == 'yes' ? 'required' : '' }}>

                                            @elseif($cf->field_type == 'selectbox')
                                                @php $values = explode(",",$cf->default_valus); @endphp

                                                <select class="form-control select2{{ $errors->has('custom_field.'.$name) ? ' is-invalid' : '' }}" name="custom_field[{{ $name }}]"  {{ $cf->validation_rules == 'yes' ? 'required' : '' }}>
                                                    <option value="">{{ _lang('Select One') }}</option>
                                                   @foreach($values as $value)
                                                        <option value="{{ $value }}">
                                                            {{ ucwords($value) }}
                                                        </option>
                                                   @endforeach
                                                </select>

                                            @endif

                                            @if ($errors->has('custom_field.'.$name))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('custom_field.'.$name) }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                @if($section_id != 0)
                                    </div>
                                @endif

                            @endforeach
                    

                        <div class="form-group row mt-5">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary btn-login">
                                    {{ _lang('Create My Account') }}
                                </button>
                            </div>
                        </div>

                        <div class="form-group row mt-5">
                            <div class="col-md-12 text-center">
                                {{ _lang('Already have an account?') }} 
                                <a href="{{ url('login') }}">
                                    {{ _lang('Log in here') }}
                                </a>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-script')

<script>
$(document).on('change','#account_type',function(){
	if($(this).val() == 'business'){
		$("#business_name").removeClass('d-none');
	}else{
		$("#business_name").addClass('d-none');
	}
});
</script>
@endsection
