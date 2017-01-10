<?php

namespace Test\Stub\Billing;

use App\Billing\Contracts\UserAccountInterface;
use App\Billing\Contracts\ManagedAccountInterface;

class StubManagedAccount extends StubUserAccount implements
    ManagedAccountInterface,
    UserAccountInterface
{
    //
}
