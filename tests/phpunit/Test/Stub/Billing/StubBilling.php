<?php

namespace Test\Stub\Billing;

use App\Billing\Contracts\BillingInterface;
use App\Billing\Contracts\ChargableInterface;

class StubBilling implements BillingInterface
{
    public function account($user)
    {
        return new StubCustomerAccount($user);
    }

    public function charge($account, ChargableInterface $product)
    {
        return new StubCharge($account, $product);
    }
}


