<a class="nav-link" href="{{ url('dashboard') }}">
    <div class="sb-nav-link-icon">
        <i data-feather="activity">
        </i>
    </div>
    {{ _lang('Account Overview') }}
</a>

<a class="nav-link" href="{{ url('user/overview') }}">
    <div class="sb-nav-link-icon">
        <i data-feather="user">
        </i>
    </div>
    {{ _lang('Profile Overview') }}
</a>


<a aria-controls="collapseLayouts" aria-expanded="false" class="nav-link collapsed" data-target="#payment_request" data-toggle="collapse" href="#">
    <div class="sb-nav-link-icon">
        <i data-feather="check-circle">
        </i>
    </div>
    {{ _lang('Payment Request') }}
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down">
        </i>
    </div>
</a>
<div class="collapse" data-parent="#accordionSidenav" id="payment_request">
    <nav class="sb-sidenav-menu-nested nav accordion">
        <a class="nav-link" href="{{ route('payment_requests.create') }}">
            {{ _lang('New Request') }}
        </a>
        <a class="nav-link" href="{{ route('payment_requests.index') }}">
            {{ _lang('Requested Payment') }}
        </a>
    </nav>
</div>

<a aria-controls="collapseLayouts" aria-expanded="false" class="nav-link collapsed" data-target="#transfer" data-toggle="collapse" href="#">
    <div class="sb-nav-link-icon">
        <i data-feather="send">
        </i>
    </div>
    {{ _lang('Money Transfer') }}
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down">
        </i>
    </div>
</a>
<div class="collapse" data-parent="#accordionSidenav" id="transfer">
    <nav class="sb-sidenav-menu-nested nav accordion">
        <a class="nav-link" href="{{ url('user/transfer_between_accounts') }}">
            {{ _lang('Between Accounts') }}
        </a>
        <a class="nav-link" href="{{ url('user/transfer_between_users') }}">
            {{ _lang('Between Users') }}
        </a>
        <a class="nav-link" href="{{ url('user/card_funding_transfer') }}">
            {{ _lang('Card Funding Transfer') }}
        </a>
        <a class="nav-link" href="{{ url('user/outgoing_wire_transfer') }}">
            {{ _lang('Wire Transfer') }}
        </a>
    </nav>
</div>

<a class="nav-link" href="{{ url('user/my_loans') }}">
    <div class="sb-nav-link-icon">
        <i data-feather="dollar-sign"></i>
    </div>
    {{ _lang('My Loans') }}
</a>

<a aria-controls="collapseLayouts" aria-expanded="false" class="nav-link collapsed" data-target="#load_money" data-toggle="collapse" href="#">
    <div class="sb-nav-link-icon">
        <i data-feather="credit-card">
        </i>
    </div>
    {{ _lang('Load Money') }}
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down">
        </i>
    </div>
</a>
<div class="collapse" data-parent="#accordionSidenav" id="load_money">
    <nav class="sb-sidenav-menu-nested nav accordion">
        @if(get_option('paypal_active') == 'Yes')
            <a class="nav-link" href="{{ url('user/load_money/paypal') }}">
                {{ _lang('Via PayPal') }}
            </a>
        @endif

        @if(get_option('stripe_active') == 'Yes')
            <a class="nav-link" href="{{ url('user/load_money/stripe') }}">
                {{ _lang('Via Stripe') }}
            </a>
        @endif

        @if(get_option('blockchain_active') == 'Yes')
            <a class="nav-link" href="{{ url('user/load_money/blockchain') }}">
                {{ _lang('Via BlockChain') }}
            </a>
        @endif

        @if(get_option('wire_transfer_active') == 'Yes')
            <a class="nav-link" href="{{ url('user/load_money/wire_transfer') }}">
                {{ _lang('Via Wire Transfer') }}
            </a>
        @endif
    </nav>
</div>

<a aria-controls="collapseLayouts" aria-expanded="false" class="nav-link collapsed" data-target="#gift_card" data-toggle="collapse" href="#">
    <div class="sb-nav-link-icon">
        <i data-feather="gift">
        </i>
    </div>
    {{ _lang('Gift Card') }}
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down">
        </i>
    </div>
</a>
<div class="collapse" data-parent="#accordionSidenav" id="gift_card">
    <nav class="sb-sidenav-menu-nested nav accordion">
        <a class="nav-link" href="{{ route('gift_cards.create') }}">
            {{ _lang('Create New') }}
        </a>
		<a class="nav-link" href="{{ url('user/gift_cards/redeem') }}">
            {{ _lang('Redeem Gift Card') }}
        </a>
        <a class="nav-link" href="{{ url('gift_cards/status/active_gift_card') }}">
            {{ _lang('Active Gift Cards') }}
        </a>
         <a class="nav-link" href="{{ url('gift_cards/status/used_gift_card') }}">
            {{ _lang('Used Gift Cards') }}
        </a>
    </nav>
</div>

<a class="nav-link" href="{{ url('user/referral_commissions') }}">
    <div class="sb-nav-link-icon">
        <i data-feather="trending-up">
        </i>
    </div>
    {{ _lang('Commissions') }} <span class="badge badge-danger ml-2">{{ referral_commission_count() }}</span>
</a>

<a aria-controls="collapseLayouts" aria-expanded="false" class="nav-link collapsed" data-target="#message" data-toggle="collapse" href="#">
    <div class="sb-nav-link-icon">
        <i data-feather="message-square">
        </i>
    </div>
    {{ _lang('Message') }}
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down">
        </i>
    </div>
</a>
<div class="collapse" data-parent="#accordionSidenav" id="message">
    <nav class="sb-sidenav-menu-nested nav accordion">
        <a class="nav-link" href="{{ url('message/compose') }}">
            {{ _lang('Compose') }}
        </a>
        <a class="nav-link" href="{{ url('message/inbox') }}">
            {{ _lang('Inbox') }}
        </a>
        <a class="nav-link" href="{{ url('message/outbox') }}">
            {{ _lang('Outbox') }}
        </a>
    </nav>
</div>

<a aria-controls="collapseLayouts" aria-expanded="false" class="nav-link collapsed" data-target="#reports" data-toggle="collapse" href="#">
    <div class="sb-nav-link-icon">
        <i data-feather="bar-chart-2">
        </i>
    </div>
    {{ _lang('Reports') }}
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down">
        </i>
    </div>
</a>
<div class="collapse" data-parent="#accordionSidenav" id="reports">
    <nav class="sb-sidenav-menu-nested nav accordion">
        <a class="nav-link" href="{{ url('user/reports/account_statement') }}">
            {{ _lang('Account Statement') }}
        </a>
        <a class="nav-link" href="{{ url('user/reports/all_transaction') }}">
            {{ _lang('All Transaction') }}
        </a>
        <a class="nav-link" href="{{ url('user/reports/referred_users') }}">
            {{ _lang('My Referred Users') }}
        </a>
    </nav>
</div>

<a class="nav-link" href="{{ url('logout') }}">
    <div class="sb-nav-link-icon">
        <i data-feather="log-out">
        </i>
    </div>
    {{ _lang('Logout') }}
</a>
