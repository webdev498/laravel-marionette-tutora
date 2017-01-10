<?php namespace App\Billing\Exceptions;

use App\Exceptions\AppException;
use App\Billing\Contracts\Exceptions\BillingExceptionInterface;

class BillingException extends AppException implements
    BillingExceptionInterface
{
    //
}
