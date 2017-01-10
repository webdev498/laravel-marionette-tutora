<?php

namespace Test\Stub\Billing;

use App\Billing\Contracts\ChargeInterface;
use App\Billing\Exceptions\BillingException;
use App\Billing\Contracts\ChargableInterface;

class StubCharge implements ChargeInterface
{
    public $id = 'stubbed';

    protected $customer;

    protected $product;

    public function __construct($customer, $product)
    {
        $this->customer = $customer;
        $this->product  = $product;
    }

    public function payee($payee)
    {
        //
    }

    public function fee($fee)
    {
        //
    }

    public function authorise()
    {
        if ($this->customer->user->last_four === 3417) {
            throw new BillingException();
        }
    }
}
