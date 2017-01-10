<?php namespace App\Billing;

use Stripe\Charge;
use App\Billing\Contracts\ChargeInterface;
use App\Billing\Exceptions\BillingException;
use App\Billing\Contracts\ChargableInterface;
use App\Billing\Contracts\ManagedAccountInterface;
use App\Billing\Contracts\CustomerAccountInterface;

class StripeCharge extends AbstractStripe implements ChargeInterface
{

    use AttributeProxyTrait;

    /**
     * @var CustomerAccountInterface
     */
    protected $customer;

    /**
     * @var ChargeInterface
     */
    protected $product;

    /**
     * @var Integer
     */
    protected $amount;

    /**
     * @var Charge
     */
    protected $charge;

    /**
     * @var ManagedAccountInterface
     */
    protected $payee;

    /**
     * @var Integer
     */
    protected $fee;

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
     * @return mixed
     */
    protected function getAttributeProxyObject()
    {
        return $this->charge;
    }

    /**
     * Create an instance of a stripe charge
     *
     * @param  CustomerAccountInterface $customer
     * @param  ChargableInterface       $product
     * @return void
     */
    public function __construct(
        CustomerAccountInterface $customer,
        ChargableInterface       $product
    ) {
        $this->customer = $customer;
        $this->product  = $product;

        $this->amount = $product->getChargeAmount();
    }

    /**
     * Payout the fee to a given payee
     *
     * @param  ManagedAccountInterface $payee
     * @return self
     */
    public function payee(ManagedAccountInterface $payee)
    {
        $this->payee = $payee;

        return $this;
    }

    /**
     * Apply a fee to the charge. This can be in the form of a percentage
     * or an absolute number.
     *
     * @param  Mixed $fee
     * @return self
     */
    public function fee($fee)
    {
        $this->fee = $this->valueOfAmount($fee);

        return $this;
    }

    /**
     * Apply a discount to the charge. This can be in the form of a percentage
     * or an absolute number.
     *
     * @param  mixed $discount
     * @return self
     */
    public function discount($discount)
    {
        $discount = $this->valueOfAmount($discount);

        $this->amount = bcsub($this->amount, $discount);

        return $this;
    }

    /**
     * Get an absolute value from a percentage of the amount. If an absolute
     * value is passed, return the value in pence (x100).
     *
     * @param  mixed $value
     * @return integer
     */
    protected function valueOfAmount($value)
    {
        if (ends_with($value, '%')) {
            $percent = substr($value, 0, -1);

            $value = bcdiv($percent, 100, strlen($percent));
            $value = bcmul($this->amount, $value);
        } else {
            $value = bcmul($value, 100);
        }

        return $value;
    }

    /**
     * Charge now with Stripe
     */
    public function now()
    {
        return $this->proxyStripeExceptions(function () {
            $options = [
                'capture'     => true,
                'amount'      => $this->amount,
                'currency'    => 'gbp',
                'customer'    => $this->customer->id,
                'description' => $this->product->getChargeDescription(),
                'statement_descriptor' => $this->product->getChargeDescription()
            ];

            if ($this->fee) {
                array_set($options, 'application_fee', $this->fee);
            }

            if ($this->payee) {
                array_set($options, 'destination', $this->payee->id);
            }

            $this->charge = Charge::create($options, [
                'idempotency_key' => $this->product->getIdempotencyKey(),
            ]);

            return true;
        });
    }

    /**
     * Authorise a charge with Stripe
     *
     * @return Boolean
     */
    public function authorise()
    {
        return $this->proxyStripeExceptions(function () {
            $options = [
                'capture'     => false,
                'amount'      => $this->amount,
                'currency'    => 'gbp',
                'customer'    => $this->customer->id,
                'description' => $this->product->getChargeDescription(),
                'statement_descriptor' => $this->product->getChargeDescription(),
            ];

            if ($this->fee) {
                array_set($options, 'application_fee', $this->fee);
            }

            if ($this->payee) {
                array_set($options, 'destination', $this->payee->id);
            }

            $this->charge = Charge::create($options, [
                'idempotency_key' => $this->product->getIdempotencyKey(),
            ]);

            return true;
        });
    }

    /**
     * Capture an authorised charge with Stripe
     *
     * @return Boolean
     */
    public function capture()
    {
        return $this->proxyStripeExceptions(function () {
            $options = [
                'amount' => $this->amount,
            ];

            if ($this->fee) {
                array_set($options, 'application_fee', $this->fee);
            }

            $charge = $this->retrieve();
            $charge->capture($options);
            return true;
        });
    }

    /**
     * Refund a charge with Stripe
     *
     * @return Boolean
     */
    public function refund()
    {
        return $this->proxyStripeExceptions(function () {
            $charge = $this->retrieve();
            $charge->refunds->create();
            return true;
        });
    }

    /**
     * Retrieve a charge on a product from Stripe
     *
     * @return Charge
     */
    public function retrieve()
    {
        return $this->proxyStripeExceptions(function () {
            $id = $this->product->charge_id;

            if ( ! $id) {
                throw new BillingException("There isn't a charge to capture.");
            }

            return $this->charge = Charge::retrieve($this->product->charge_id);
        });
    }

}
