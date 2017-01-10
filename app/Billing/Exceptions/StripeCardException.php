<?php namespace App\Billing\Exceptions;

use App\Billing\Contracts\Exceptions\CardExceptionInterface;

class StripeCardException extends StripeBillingException
    implements CardExceptionInterface
{
}
