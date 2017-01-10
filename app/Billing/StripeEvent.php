<?php namespace App\Billing;

use App\Billing\Contracts\EventInterface;
use App\Billing\Exceptions\BillingException;

class StripeEvent extends AbstractStripe implements EventInterface
{

    use AttributeProxyTrait;

    /**
     * @var StripeEvent
     */
    protected $event;

    /**
     * What attributes are visible form the proxied object?
     *
     * @var array
     */
    protected $visible = [
        'id',
    ];

    /**
     * Get the object to proxy on
     *
     * @return StripeEvent
     */
    protected function getAttributeProxyObject()
    {
        return $this->event;
    }

    /**
     * Retrieve an event by id
     *
     * @param  mixed $id
     * @return self
     */
    public function retrieve($id)
    {
        $this->event = \Stripe\Event::retrieve($id);
        return $this;
    }

    public function exists($id)
    {
        try {
            return $this->proxyStripeExceptions(function () use ($id) {
                $this->retrieve($id);
                return $this->event === null;
            });
        } catch (BillingException $e) {
            return false;
        }
    }
}
