<?php

namespace Test\Stub\Billing;

use App\Billing\Contracts\UserAccountInterface;
use App\Billing\Contracts\CustomerAccountInterface;

class StubCustomerAccount extends StubUserAccount implements
    CustomerAccountInterface,
    UserAccountInterface
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
}
