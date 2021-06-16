<a class="nav-link" href="{{ url('dashboard') }}">
    <div class="sb-nav-link-icon">
        <i data-feather="activity">
        </i>
    </div>
    {{ _lang('Dashboard') }}
</a>
<a aria-controls="collapseLayouts" aria-expanded="false" class="nav-link collapsed" data-target="#user-management" data-toggle="collapse" href="#">
    <div class="sb-nav-link-icon">
        <i data-feather="users">
        </i>
    </div>
    {{ _lang('User Management') }}
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down">
        </i>
    </div>
</a>
<div class="collapse" data-parent="#accordionSidenav" id="user-management">
    <nav class="sb-sidenav-menu-nested nav accordion">
        <a class="nav-link" href="{{ url('admin/users/create') }}">
            {{ _lang('Add New') }}
        </a>
        <a class="nav-link" href="{{ url('admin/users/status/Verified') }}">
            {{ _lang('Verified Users') }}
        </a>
        <a class="nav-link" href="{{ url('admin/users/status/Unverified') }}">
            {{ _lang('Unvarified Users') }}
        </a>
        <a class="nav-link" href="{{ url('admin/users') }}">
            {{ _lang('All Users') }}
        </a>
    </nav>
</div>
<a class="nav-link" href="{{ url('admin/users/documents') }}">
    <div class="sb-nav-link-icon">
        <i data-feather="file-text">
        </i>
    </div>
    {{ _lang('User Documents') }}
</a>

<a aria-controls="collapseLayouts" aria-expanded="false" class="nav-link collapsed" data-target="#transfer_request" data-toggle="collapse" href="#">
    <div class="sb-nav-link-icon">
        <i data-feather="repeat">
        </i>
    </div>
    {{ _lang('Transfer Request') }} <span class="badge badge-danger ml-2">{{ transfer_request_count() }}</span>
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down">
        </i>
    </div>
</a>

<div class="collapse" data-parent="#accordionSidenav" id="transfer_request">
    <nav class="sb-sidenav-menu-nested nav accordion">
        <a class="nav-link" href="{{ url('admin/transfer_request') }}">
            {{ _lang('Pending Request') }}
        </a>
        <a class="nav-link" href="{{ url('admin/transfer_request/reject') }}">
            {{ _lang('Rejected Request') }}
        </a>
    </nav>
</div>


<a aria-controls="collapseLayouts" aria-expanded="false" class="nav-link collapsed" data-target="#deposit_request" data-toggle="collapse" href="#">
    <div class="sb-nav-link-icon">
        <i data-feather="plus-circle"></i>
    </div>
    {{ _lang('Deposit Request') }} <span class="badge badge-danger ml-2">{{ deposit_request_count() }}</span>
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down">
        </i>
    </div>
</a>

<div class="collapse" data-parent="#accordionSidenav" id="deposit_request">
    <nav class="sb-sidenav-menu-nested nav accordion">
        <a class="nav-link" href="{{ url('admin/deposit/request') }}">
            {{ _lang('Pending Request') }}
        </a>
        <a class="nav-link" href="{{ url('admin/deposit/request/reject') }}">
            {{ _lang('Rejected Request') }}
        </a>
    </nav>
</div>


<a aria-controls="collapseLayouts" aria-expanded="false" class="nav-link collapsed" data-target="#accounts" data-toggle="collapse" href="#">
    <div class="sb-nav-link-icon">
        <i data-feather="home">
        </i>
    </div>
    {{ _lang('Accounts') }}
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down">
        </i>
    </div>
</a>

<div class="collapse" data-parent="#accordionSidenav" id="accounts">
    <nav class="sb-sidenav-menu-nested nav accordion">
        <a class="nav-link" href="{{ url('admin/accounts') }}">
            {{ _lang('Account List') }}
        </a>
        <a class="nav-link" href="{{ url('admin/account_types') }}">
            {{ _lang('Account Types') }}
        </a>
         <a class="nav-link" href="{{ url('admin/currency') }}">
            {{ _lang('Currency List') }}
        </a>
    </nav>
</div>

<a aria-controls="collapseLayouts" aria-expanded="false" class="nav-link collapsed" data-target="#cards" data-toggle="collapse" href="#">
    <div class="sb-nav-link-icon">
        <i data-feather="credit-card">
        </i>
    </div>
    {{ _lang('Cards') }}
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down">
        </i>
    </div>
</a>

<div class="collapse" data-parent="#accordionSidenav" id="cards">
    <nav class="sb-sidenav-menu-nested nav accordion">
        <a class="nav-link" href="{{ route('cards.index') }}">
            {{ _lang('Card List') }}
        </a>
        <a class="nav-link" href="{{ route('card_types.index') }}">
            {{ _lang('Card Types') }}
        </a>
        <a class="nav-link" href="{{ route('card_transactions.index') }}">
            {{ _lang('Card Transactions') }}
        </a>
    </nav>
</div>


<a aria-controls="collapseLayouts" aria-expanded="false" class="nav-link collapsed" data-target="#deposit_method" data-toggle="collapse" href="#">
    <div class="sb-nav-link-icon">
        <i data-feather="plus-circle">
        </i>
    </div>
    {{ _lang('Deposit') }}
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down">
        </i>
    </div>
</a>

<div class="collapse" data-parent="#accordionSidenav" id="deposit_method">
    <nav class="sb-sidenav-menu-nested nav accordion">
        <a class="nav-link" href="{{ url('admin/deposit/create') }}">
            {{ _lang('Make Deposit') }}
        </a>
        <a class="nav-link" href="{{ url('admin/deposit') }}">
            {{ _lang('Deposit History') }}
        </a>
    </nav>
</div>
<a aria-controls="collapseLayouts" aria-expanded="false" class="nav-link collapsed" data-target="#withdraw_method" data-toggle="collapse" href="#">
    <div class="sb-nav-link-icon">
        <i data-feather="minus-circle">
        </i>
    </div>
    {{ _lang('Withdraw') }}
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down">
        </i>
    </div>
</a>

<div class="collapse" data-parent="#accordionSidenav" id="withdraw_method">
    <nav class="sb-sidenav-menu-nested nav accordion">
        <a class="nav-link" href="{{ url('admin/withdraw/create') }}">
            {{ _lang('Make Withdraw') }}
        </a>
        <a class="nav-link" href="{{ url('admin/withdraw') }}">
            {{ _lang('Withdraw History') }}
        </a>
    </nav>
</div>

<a aria-controls="collapseLayouts" aria-expanded="false" class="nav-link collapsed" data-target="#fees" data-toggle="collapse" href="#">
    <div class="sb-nav-link-icon">
        <i data-feather="dollar-sign">
        </i>
    </div>
    {{ _lang('Fees') }}
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down">
        </i>
    </div>
</a>

<div class="collapse" data-parent="#accordionSidenav" id="fees">
    <nav class="sb-sidenav-menu-nested nav accordion">
        <a class="nav-link" href="{{ route('custom_fees.create') }}">
            {{ _lang('Charge New Fee') }}
        </a>
        <a class="nav-link" href="{{ route('custom_fees.index') }}">
            {{ _lang('Fees History') }}
        </a>
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
        <a class="nav-link" href="{{ url('gift_cards/status/active_gift_card') }}">
            {{ _lang('Active Gift Cards') }}
        </a>
         <a class="nav-link" href="{{ url('gift_cards/status/used_gift_card') }}">
            {{ _lang('Used Gift Cards') }}
        </a>
    </nav>
</div>

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

<a aria-controls="collapseLayouts" aria-expanded="false" class="nav-link collapsed" data-target="#loan" data-toggle="collapse" href="#">
    <div class="sb-nav-link-icon">
        <i data-feather="bar-chart-2"></i>
    </div>
    {{ _lang('Loan Management') }}</span>
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down">
        </i>
    </div>
</a>

<div class="collapse" data-parent="#accordionSidenav" id="loan">
    <nav class="sb-sidenav-menu-nested nav accordion">
        <a class="nav-link" href="{{ route('loans.calculator') }}">
            {{ _lang('Loan Calculator') }}
        </a>
        <a class="nav-link" href="{{ route('loans.index') }}">
            {{ _lang('Loans') }}
        </a>
         <a class="nav-link" href="{{ route('loan_payments.index') }}">
            {{ _lang('Repayments') }}
        </a>
        <a class="nav-link" href="{{ route('loan_products.index') }}">
            {{ _lang('Loan Product') }}
        </a>
    </nav>
</div>

<a aria-controls="collapseLayouts" aria-expanded="false" class="nav-link collapsed" data-target="#accounting" data-toggle="collapse" href="#">
    <div class="sb-nav-link-icon">
        <i data-feather="bar-chart-2"></i>
    </div>
    {{ _lang('Accounting') }}</span>
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down">
        </i>
    </div>
</a>

<div class="collapse" data-parent="#accordionSidenav" id="accounting">
    <nav class="sb-sidenav-menu-nested nav accordion">
        <a class="nav-link" href="{{ route('income.index') }}">
            {{ _lang('Income') }}
        </a>
         <a class="nav-link" href="{{ route('expense.index') }}">
            {{ _lang('Expense') }}
        </a>
        <a class="nav-link" href="{{ route('category.index') }}">
            {{ _lang('Category') }}
        </a>
    </nav>
</div>


<a aria-controls="collapseLayouts" aria-expanded="false" class="nav-link collapsed" data-target="#reports" data-toggle="collapse" href="#">
    <div class="sb-nav-link-icon">
        <i data-feather="pie-chart">
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
        <a class="nav-link" href="{{ url('admin/reports/account_statement') }}">
            {{ _lang('Account Statement') }}
        </a>
        <a class="nav-link" href="{{ url('admin/reports/transactions_report') }}">
            {{ _lang('Transactions Report') }}
        </a>
		<a class="nav-link" href="{{ url('admin/reports/profit_and_loss_report') }}">
            {{ _lang('Profit and Loss') }}
        </a>
		<a class="nav-link" href="{{ url('admin/reports/profit_report_by_user') }}">
            {{ _lang('Profit By User') }}
        </a>
        <a class="nav-link" href="{{ url('admin/reports/deposit_report') }}">
            {{ _lang('Deposit Report') }}
        </a>
        <a class="nav-link" href="{{ url('admin/reports/withdraw_report') }}">
            {{ _lang('Withdraw Report') }}
        </a>
        <a class="nav-link" href="{{ url('admin/reports/wire_transfer_report') }}">
            {{ _lang('Wire Transfer Report') }}
        </a>
    </nav>
</div>


<a aria-controls="collapseLayouts" aria-expanded="false" class="nav-link collapsed" data-target="#staff-management" data-toggle="collapse" href="#">
    <div class="sb-nav-link-icon">
        <i data-feather="users">
        </i>
    </div>
    {{ _lang('Manager & Admin') }}
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down">
        </i>
    </div>
</a>
<div class="collapse" data-parent="#accordionSidenav" id="staff-management">
    <nav class="sb-sidenav-menu-nested nav accordion">
        <a class="nav-link" href="{{ url('admin/staffs/create') }}">
            {{ _lang('Add New') }}
        </a>
        <a class="nav-link" href="{{ url('admin/staffs') }}">
            {{ _lang('Manager & Admins') }}
        </a>
    </nav>
</div>


<a aria-controls="collapseLayouts" aria-expanded="false" class="nav-link collapsed" data-target="#administration" data-toggle="collapse" href="#">
    <div class="sb-nav-link-icon">
        <i data-feather="settings">
        </i>
    </div>
    {{ _lang('Administration') }}
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down">
        </i>
    </div>
</a>
<div class="collapse" data-parent="#accordionSidenav" id="administration">
    <nav class="sb-sidenav-menu-nested nav accordion">
        <a class="nav-link" href="{{ url('admin/administration/general_settings') }}">
            {{ _lang('General Settings') }}
        </a>
        <a class="nav-link" href="{{ url('admin/administration/message_template') }}">
            {{ _lang('Message Template') }}
        </a>
        <a class="nav-link" href="{{ url('admin/languages') }}">
            {{ _lang('Language Management') }}
        </a>
        <a class="nav-link" href="{{ url('admin/administration/backup_database') }}">
            {{ _lang('Database Backup') }}
        </a>
    </nav>
</div>
