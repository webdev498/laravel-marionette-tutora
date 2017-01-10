<?php namespace App\Billing;

use Stripe\Refund;
use App\Billing\Contracts\RefundInterface;
use App\Billing\Contracts\ChargeInterface;
use App\Billing\Exceptions\BillingException;
use App\Billing\Contracts\ChargableInterface;
use App\Billing\Contracts\ManagedAccountInterface;
use App\Billing\Contracts\CustomerAccountInterface;

class StripeRefund extends AbstractStripe implements RefundInterface
{
    /**
     * @var Charge
     */
    protected $charge;

    /**
     * @var Refund
     */
    protected $refund;

    /**
     * Create an instance of a Stripe refund
     *
     * @param  ChargableInterface       $product
     * @return void
     */
    public function __construct(
        ChargableInterface       $product
    ) {
        $this->product  = $product;
    }

	function now($reverse_transfer = true, $amount = null) 
	{
		return $this->proxyStripeExceptions(function () use ($reverse_transfer, $amount) {
			
			$options = [
                'amount'                => $amount ? ($amount * 100) : null,   // If null, refund full amount
                'charge'     			=> $this->product->getChargeId(),
                'refund_application_fee'=> $reverse_transfer ? true : false,
                'reverse_transfer' 		=> $reverse_transfer ? true : false,
                'reason'				=> 'requested_by_customer'
            ];

            loginfo($options);

			$this->refund = Refund::create($options, [
                'idempotency_key' => $this->product->getIdempotencyKey(),
            ]);
			return true;
		});
	}
}