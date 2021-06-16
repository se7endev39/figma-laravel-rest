<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use App\Transaction;

class TransferRequestApproved
{
    use SerializesModels;

    public $transaction;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }
}
