<?php namespace App\Billing;

use App\Billing\Contracts\CardInterface;
use App\Billing\Contracts\CustomerAccountInterface;

class StripeCard extends AbstractStripe implements CardInterface
{

    use AttributeProxyTrait;

    /**
     * @var CustomerAccountInterface
     */
    protected $account;

    /**
     * @var Card
     */
    protected $card;

    /**
     * What attributes are visible form the proxied object?
     *
     * @var array
     */
    protected $visible = [
        'last_four',
    ];

    /**
     * @param  CustomerAccountInterface $account
     * @return void
     */
    public function __construct(CustomerAccountInterface $account)
    {
        $this->account = $account;
    }

    /**
     * Get the object to proxy on
     *
     * @return mixed
     */
    protected function getAttributeProxyObject()
    {
        return $this->card;
    }

    /**
     * Create a card, with Stripe
     *
     * @param  String $token
     * @return Boolean
     */
    public function create($token)
    {
        return $this->proxyStripeExceptions(function () use ($token) {
            $this->deleteExistingCards();

            $this->card = $this->account->sources->create([
                'source' => $token,
            ]);

            return true;
        });
    }

    protected function deleteExistingCards()
    {
        return $this->proxyStripeExceptions(function () {
            if ($this->account->sources->total_count > 0) {
                $cards = $this->account->sources->all();

                foreach ($cards->data as $card) {
                    $card->delete();
                }
            }
        });
    }

    public function getLastFourAttribute()
    {
        if (isset($this->card)) {
            return $this->card->last4;
        } else if (
            $this->account                &&
            $this->account->sources       &&
            $this->account->sources->data &&
            count($this->account->sources->data) === 1
        ) {
            return $this->account->sources->data[0]->last4;
        }
    }

}
