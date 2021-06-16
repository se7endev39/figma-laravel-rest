@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
    <div class="container-fluid">
        <div class="sb-page-header-content py-5">
            <h1 class="sb-page-header-title">
                <div class="sb-page-header-icon">
                    <i data-feather="inbox">
                    </i>
                </div>
                <span>
                    {{ _lang('Inbox') }}
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
                    <!--<form action="" class="float-right mail-search" method="get">
                        <div class="input-group">
                            <input class="form-control form-control-sm" name="search" placeholder="Search email" type="text">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-secondary" type="submit">
                                        {{ _lang('Search') }}
                                    </button>
                                </div>
                            </input>
                        </div>
                    </form>-->
                    <h2>
                        {{ _lang('Inbox') }} ({{ $unread_item_count }})
                    </h2>
                    <div class="mail-tools tooltip-demo m-t-md">
                        <div class="btn-group float-right">
                            <a href="{{ $conversations->previousPageUrl() }}" class="btn btn-white btn-sm {{ $conversations->onFirstPage() == 1 ? 'disabled' : '' }}">
                                <i class="fa fa-arrow-left">
                                </i>
                            </a>
                            <a href="{{ $conversations->nextPageUrl() }}" class="btn btn-white btn-sm  {{ $conversations->hasMorePages() != 1 ? 'disabled' : '' }}">
                                <i class="fa fa-arrow-right">
                                </i>
                            </a>
                        </div>
                        <a href="{{ url('message/inbox') }}" class="btn btn-white btn-sm" id="refresh_inbox" data-placement="left" data-toggle="tooltip" title="Refresh inbox">
                            <i class="fas fa-sync"></i>
                        </a>
                        <button class="btn btn-white btn-sm" id="mark_as_read" data-placement="top" data-toggle="tooltip" title="Mark as read">
                            <i class="fa fa-eye">
                            </i>
                        </button>
                        <button class="btn btn-white btn-sm" id="move_to_trash" data-placement="top" data-toggle="tooltip" title="Move to trash">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </div>
                </div>

                <div class="mail-box">
                    <table class="table table-hover table-mail">
                        <tbody>
                            <form method="post" id="bulk_form" action="{{ url('message/bulk_action') }}">
                                {{ csrf_field() }}

                                
                                @if( $conversations->isEmpty() )
                                   <tr>
                                        <td colspan="4"><p class="text-center">{{ _lang('No message found !') }}</h5></td>
                                   </tr>
                                @endif

                                @foreach($conversations as $conversation)
                                    
                                    @php $message = $conversation->messages[0]; @endphp


                                    <tr {{ $message->is_seen == 1 ? 'class=read' : 'class=unread' }}>
                                        <td class="check-mail">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" name="conversation_id[]" value="{{ $conversation->id }}" id="mail_{{ $conversation->id }}" type="checkbox">
                                                <label class="custom-control-label" for="mail_{{ $conversation->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="mail-ontact">
                                            <a href="{{ url('message/view_inbox/'.$conversation->id) }}">
                                                {{ $conversation->sender->first_name.' '.$conversation->sender->last_name }}
                                            </a>
                                        </td>
                                        <td class="mail-subject">
                                            <a href="{{ url('message/view_inbox/'.$conversation->id) }}">
                                                {{ \Str::limit($conversation->subject,50) }}
                                            </a>
                                        </td>
                                        <td class="text-right mail-date">
                                            {{ $message->getHumansTimeAttribute().' '._lang('ago') }}
                                        </td>
                                    </tr>

                                @endforeach
                                <input type="hidden" name="action" id="action" value=""/>
                            </form>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-script')
<script>
   $(document).on('click','#mark_as_read',function(){
        $("#action").val("mark_as_read");
        $("#bulk_form").submit();
   }); 

    $(document).on('click','#move_to_trash',function(){
        $("#action").val("move_to_trash");
        $("#bulk_form").submit();
   });

   

</script>
@endsection
