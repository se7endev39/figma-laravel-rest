<?php

namespace App\Utilities;

class LoanCalculator
{

	public $payable_amount;
	private $apply_amount;
	private $first_payment_date;
	private $interest_rate;
	private $term;
	private $term_period;
	private $late_payment_penalties;

	public function __construct($apply_amount, $first_payment_date, $interest_rate, $term, $term_period, $late_payment_penalties){
		$this->apply_amount = $apply_amount;
		$this->first_payment_date = $first_payment_date;
		$this->interest_rate = $interest_rate;
		$this->term = $term;
		$this->term_period = $term_period;
		$this->late_payment_penalties = $late_payment_penalties;
	}


	public function get_flat_rate(){
		$this->payable_amount = (($this->interest_rate / 100) * $this->apply_amount) + $this->apply_amount;

        $date = $this->first_payment_date;
        $principle_amount = $this->apply_amount / $this->term;
        $amount_to_pay = $principle_amount + (($this->interest_rate / 100) * $principle_amount);
        $interest = (($this->interest_rate / 100) * $this->apply_amount) / $this->term;
        $balance = $this->payable_amount;
        $penalty = (($this->late_payment_penalties / 100) * $this->apply_amount);  

        $data = array();
        for($i = 0; $i < $this->term; $i++){
            $balance = $balance - $amount_to_pay;
            $data[] = array(
                'date'              => $date,
                'amount_to_pay'     => $amount_to_pay,
                'penalty'           => $penalty,
                'principle_amount'  => $principle_amount,
                'interest'          => $interest,
                'balance'           => $balance,
            );

            $date = date("Y-m-d", strtotime($this->term_period, strtotime($date)));     
        }

        return $data;
	}

	public function get_fixed_rate(){
		$this->payable_amount = ((($this->interest_rate / 100) * $this->apply_amount) * $this->term) + $this->apply_amount;
        $date = $this->first_payment_date;
        $principle_amount = $this->apply_amount / $this->term;
        $amount_to_pay = $principle_amount + (($this->interest_rate / 100) * $this->apply_amount);
        $interest = (($this->interest_rate / 100) * $this->apply_amount);
        $balance = $this->payable_amount ;
        $penalty = (($this->late_payment_penalties / 100) * $this->apply_amount);  

        $data = array();
        for($i = 0; $i < $this->term; $i++){
            $balance = $balance - $amount_to_pay;
            $data[] = array(
                'date'              => $date,
                'amount_to_pay'     => $amount_to_pay,
                'penalty'           => $penalty,
                'principle_amount'  => $principle_amount,
                'interest'          => $interest,
                'balance'           => $balance,
            );

            $date = date("Y-m-d", strtotime($this->term_period, strtotime($date)));     
        }

        return $data;
	}

	public function get_mortgage(){
		$interestRate = $this->interest_rate/100;

        //Calculate the per month interest rate
        $monthlyRate = $interestRate/12;
    
        //Calculate the payment
        $payment = $this->apply_amount * ($monthlyRate / (1 - pow(1 + $monthlyRate, - $this->term)));

        $this->payable_amount = $payment * $this->term;

        $date = $this->first_payment_date;
        $balance = $this->apply_amount;
        $penalty = (($this->late_payment_penalties / 100) * $this->apply_amount);  
		
		$data = array();
        for ($count = 0; $count < $this->term; $count++){ 
            $interest = decimalPlace($balance * $monthlyRate);
            $monthlyPrincipal = decimalPlace($payment - $interest);
            $amount_to_pay = decimalPlace($interest + $monthlyPrincipal);

            $balance = $balance - $monthlyPrincipal;
            $data[] = array(
                'date'              => $date,
                'amount_to_pay'     => $amount_to_pay,
                'penalty'           => $penalty,
                'principle_amount'  => $monthlyPrincipal,
                'interest'          => $interest,
                'balance'           => $balance,
            );

            $date = date("Y-m-d", strtotime($this->term_period, strtotime($date)));     
        }

        return $data;
	}

	public function get_one_time(){
		$this->payable_amount = (($this->interest_rate / 100) * $this->apply_amount) + $this->apply_amount;
        $date = $this->first_payment_date;
        $principle_amount = $this->apply_amount;
        $amount_to_pay = $principle_amount + (($this->interest_rate / 100) * $principle_amount);
        $interest = (($this->interest_rate / 100) * $this->apply_amount);
        $balance = $this->payable_amount;
        $penalty = (($this->late_payment_penalties / 100) *$this->apply_amount);   

        $data = array();
        $balance = $balance - $amount_to_pay;
        $data[] = array(
            'date'              => $date,
            'amount_to_pay'     => $amount_to_pay,
            'penalty'           => $penalty,
            'principle_amount'  => $principle_amount,
            'interest'          => $interest,
            'balance'           => $balance,
        );

        $date = date("Y-m-d", strtotime($this->term_period, strtotime($date)));     

        return $data;
	}


}