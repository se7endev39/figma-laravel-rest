<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
		'App\Events\DepositMoney' => [
			'App\Listeners\SendDepositMoneyNotification',
		],
		'App\Events\WithdrawMoney' => [
			'App\Listeners\SendWithdrawMoneyNotification',
		],
		'App\Events\TransferRequestApproved' => [
			'App\Listeners\SendRequestApprovedNotification',
		],
		'App\Events\TransferRequestRejected' => [
			'App\Listeners\SendRequestRejectedNotification',
		],
		'App\Events\PaymentReceived' => [
			'App\Listeners\SendPaymentReceivedNotification',
		],
		'App\Events\LoanApproved' => [
			'App\Listeners\SendLoanApprovedNotification',
		],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
