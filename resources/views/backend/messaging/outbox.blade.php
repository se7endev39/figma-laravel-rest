@extends('layouts.app')

@section('content')
<div class="sb-page-header pb-10 sb-page-header-dark bg-gradient-primary-to-secondary">
    <div class="container-fluid">
        <div class="sb-page-header-content py-5">
            <h1 class="sb-page-header-title">
                <div class="sb-page-header-icon">
                    <i data-feather="mail">
                    </i>
                </div>
                <span>
                    {{ _lang('Outbox') }}
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
                        {{ _lang('Outbox') }}
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
                    </div>
                </div>
                <div class="mail-box">
                    <table class="table table-hover table-mail">
                        <tbody>
                            <form method="post" id="bulk_form" action="{{ url('message/bulk_action') }}">
                                {{ csrf_field() }}

                                @if( $conversations->isEmpty() )
                                   <tr>
                                        <td colspan="3"><p class="text-center">{{ _lang('No message found !') }}</h5></td>
                                   </tr>
                                @endif

                                @foreach($conversations as $conversation)
                                    
                                    @php $message = $conversation->messages[0]; @endphp

                                    <tr class='read'>
                                        <td class="mail-ontact" style="padding-left:20px;">
                                            <a href="{{ url('message/view_outbox/'.$conversation->id) }}">
                                                {{ $conversation->receiver->first_name.' '.$conversation->receiver->last_name }}
                                            </a>
                                        </td>
                                        <td class="mail-subject">
                                            <a href="{{ url('message/view_outbox/'.$conversation->id) }}">
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