<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use App\Transaction;


class PaymentReceived
{
    use SerializesModels;

    public $credit, $debit;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Transaction $credit, Transaction $debit)
    {
        $this->credit = $credit;
        $this->debit = $debit;
    }
}
