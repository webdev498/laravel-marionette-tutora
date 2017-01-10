<?php namespace App\Billing\Exceptions;

use App\Exceptions\AppException;
use Stripe\Error\Base as StripeBaseException;
use App\Billing\Contracts\Exceptions\BillingExceptionInterface;
use App\Billing\Contracts\Exceptions\CardExceptionInterface;

class StripeBillingException extends BillingException implements
    BillingExceptionInterface
{

    const CARD_ERROR = 'card_error';

    public function __construct(StripeBaseException $e)
    {
        $body = $e->getJsonBody();

        $this->type    = array_get($body, 'error.type', 'unknown');
        $this->code    = array_get($body, 'error.code', 'unknown');
        $this->param   = array_get($body, 'error.param', null);
        $this->message = array_get($body, 'error.message', null);

        if ( ! ($this instanceof CardExceptionInterface)) {
            if ($this->type === static::CARD_ERROR) {
                throw new StripeCardException($e);
            }
        }
    }

    public function getErrors()
    {
        return [
            [
                'field'  => $this->param,
                'code'   => $this->code,
                'detail' => $this->message
            ]
        ];
    }

}
