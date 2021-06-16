<div class="ibox">
    <div class="ibox-content mailbox-content">
        <div class="file-manager">
            <a class="btn btn-block btn-primary compose-mail" href="{{ url('message/compose') }}">
                {{ _lang('Compose Mail') }}
            </a>
            <div class="space-25">
            </div>
            <ul class="folder-list m-b-md" style="padding: 0">
                <li>
                    <a href="{{ url('message/inbox') }}">
                        <i class="fa fa-inbox ">
                        </i>
                        {{ _lang('Inbox') }}
                        <span class="label label-warning float-right">
                            {{ $unread_item_count }}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('message/outbox') }}">
                        <i class="fas fa-envelope-open-text">
                        </i>
                         {{ _lang('Send Mail') }}
                    </a>
                </li>
                
            </ul>
            <div class="clearfix">
            </div>
        </div>
    </div>
</div>