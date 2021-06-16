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
                    {{ _lang('View Message') }}
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

            @foreach($conversation->messages as $message)


            @if ($loop->first)
            <div class="col-lg-9">

                <div class="mail-box-header">
                    <!--<div class="float-right tooltip-demo">
                        <a href="" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ _lang('Remove Conversation') }}"><i class="fa fa-trash-alt"></i> </a>
                    </div>-->
                    <h2>
                        {{ _lang('View Message') }}
                    </h2>
                    <div class="mail-tools tooltip-demo m-t-md">

                        <h3>
                            <span class="font-normal">{{ _lang('Subject') }}: </span>{{ $conversation->subject }}
                        </h3>
                        <h5>
                            <span class="float-right font-normal">{{ date('h:mA d-M-Y',strtotime($message->created_at)) }}</span>
                            <span class="font-normal">{{ _lang('From') }}: </span>{{ $message->sender->first_name.' '.$message->sender->last_name }}
                        </h5>
                    </div>
                </div>
                <div class="mail-box">

                    <div class="mail-body">
                        <p>{!! $message->message !!}</p>
                    </div>

                    @if($message->attachment != '')
                    <div class="mail-attachment">
                        <p>
                            <span><i class="fa fa-paperclip"></i> {{ _lang('Attachments') }} - </span>
                            <a target="_blank" href="{{ asset('uploads/media/'.$message->attachment) }}">{{ _lang('Download') }}</a>
                        </p>

                        <div class="attachment">
                            <div class="file-box">
                                <div class="file">
                                    <a target="_blank" href="{{ asset('uploads/media/'.$message->attachment) }}">
                                        <span class="corner"></span>

                                        @if(! is_image(asset('uploads/media/'.$message->attachment)))
                                        <div class="icon">
                                            <i class="fa fa-file"></i>
                                        </div>
                                        @else
                                        <div class="image">
                                            <img alt="image" class="img-fluid" src="{{ asset('uploads/media/'.$message->attachment) }}">
                                        </div>
                                        @endif

                                        <div class="file-name">
                                            {{ $message->attachment }}
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    @endif
                    <div class="mail-body text-right tooltip-demo">
                        <a href="{{ url('message/remove/'.$message->id) }}" title="" data-placement="top" data-toggle="tooltip" data-original-title="{{ _lang('Remove') }}" class="btn btn-sm btn-white btn-remove-2"><i class="fa fa-trash-alt"></i>&nbsp;{{ _lang('Remove') }}</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            @else
            <div class="col-lg-9 offset-lg-3">
                <div class="mail-box-header">
                    <div class="mail-tools tooltip-demo">
                        <h5>
                            <span class="float-right font-normal">{{ date('h:mA d-M-Y',strtotime($message->created_at)) }}</span>
                            <span class="font-normal">{{ _lang('From') }}: </span>{{ $message->sender->first_name.' '.$message->sender->last_name }}
                        </h5>
                    </div>
                </div>
                <div class="mail-box">

                    <div class="mail-body">
                        <p>{!! $message->message !!}</p>
                    </div>

                    @if($message->attachment != '')
                    <div class="mail-attachment">
                        <p>
                            <span><i class="fa fa-paperclip"></i> {{ _lang('Attachments') }} - </span>
                            <a target="_blank" href="{{ asset('uploads/media/'.$message->attachment) }}">{{ _lang('Download') }}</a>
                        </p>

                        <div class="attachment">
                            <div class="file-box">
                                <div class="file">
                                    <a target="_blank" href="{{ asset('uploads/media/'.$message->attachment) }}">
                                        <span class="corner"></span>

                                        @if(! is_image(asset('uploads/media/'.$message->attachment)))
                                        <div class="icon">
                                            <i class="fa fa-file"></i>
                                        </div>
                                        @else
                                        <div class="image">
                                            <img alt="image" class="img-fluid" src="{{ asset('uploads/media/'.$message->attachment) }}">
                                        </div>
                                        @endif

                                        <div class="file-name">
                                            {{ $message->attachment }}
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    @endif
                    <div class="mail-body text-right tooltip-demo">
                        <a href="{{ url('message/remove/'.$message->id) }}" title="" data-placement="top" data-toggle="tooltip" data-original-title="Trash" class="btn btn-sm btn-white btn-remove-2"><i class="fa fa-trash-alt"></i>&nbsp;{{ _lang('Remove') }}</a>
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>
            @endif
            @endforeach

            <div class="col-md-9 offset-md-3">
                <!--Reply Box-->
                <form action="{{ route('messages.reply_message') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">
                                    {{ _lang('Reply Message') }}
                                </label>
                                <textarea class="form-control summernote" name="message">{{ old('message') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="file" name="attachment"/> 
                            </div>
                        </div>

                        <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                        <input type="hidden" name="" value="{{ $conversation->id }}">

                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-sm">{{ _lang('Send Reply') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!--End Reply Box-->
            </div>    

        </div>
    </div>
</div>
@endsection
