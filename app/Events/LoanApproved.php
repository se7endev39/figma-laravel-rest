<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use App\Transaction;
use App\Loan;

class LoanApproved
{
    use SerializesModels;
	
	public $transaction, $loan;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction, Loan $loan)
    {
        $this->transaction = $transaction;
        $this->loan = $loan;
    }
}
