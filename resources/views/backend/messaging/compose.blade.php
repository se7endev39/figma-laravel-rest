@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
<div class="container-fluid">
    <div class="sb-page-header-content py-5">
        <h1 class="sb-page-header-title">
            <div class="sb-page-header-icon">
                <i data-feather="send">
                </i>
            </div>
            <span>
                {{ _lang('New Message') }}
            </span>
        </h1>
    </div>
</div>
</div>
<div class="container-fluid mt-n10">
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-3">
            @include('backend.messaging.sidebar')
        </div>
        <div class="col-lg-9">
            <div class="mail-box-header">
                <h2>
                    {{ _lang('New Message') }}
                </h2>
            </div>
            <div class="mail-box p-2">
                <form action="{{ route('messages.store') }}" autocomplete="off" enctype="multipart/form-data" method="post">
                    {{ csrf_field() }}
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">
                                {{ _lang('Select Receiver') }}
                            </label>
                            <select class="form-control select2" name="receiver[]" required="true" multiple="true">
                                @if(Auth::user()->user_type == 'user')
									@foreach(\App\User::where('user_type','admin')->get() as $user)
										<option value="{{ $user->id }}">{{ $user->first_name.' '.$user->last_name.' ('.ucwords($user->user_type).')' }}</option>
									@endforeach
								@else
									@foreach(\App\User::where('id','!=',Auth::id())->get() as $user)
										<option value="{{ $user->id }}">{{ $user->first_name.' '.$user->last_name.' ('.ucwords($user->user_type).')' }}</option>
									@endforeach
								@endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">
                                {{ _lang('Subject') }}
                            </label>
                            <input class="form-control" name="subject" required="true" type="text" value="{{ old('subject') }}"/> 
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">
                                {{ _lang('Message') }}
                            </label>
                            <textarea class="form-control summernote" name="message" required="true" rows="5" type="text">{{ old('message') }}</textarea>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">
                                {{ _lang('Attachment') }}
                            </label>
                            <input type="file" class="form-control" name="attachment"/> 
                        </div>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">{{ _lang('Send Message') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
