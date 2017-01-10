<?php

namespace App\Billing;

use App\User;
use Stripe\Error\Base as StripeBaseException;
use Stripe\Error\Card as StripeCardException;
use App\Billing\Contracts\UserAccountInterface;
use Stripe\Error\ApiConnection as StripeApiConnectionException;
use Stripe\Error\InvalidRequest as StripeInvalidRequestException;
use Stripe\Error\Authentication as StripeAuthenticationException;

abstract class AbstractStripeAccount extends AbstractStripe implements
    UserAccountInterface
{

    use AttributeProxyTrait;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Account
     */
    protected $account;

    /**
     * Get the object to proxt on
     *
     * @return mixed
     */
    protected function getAttributeProxyObject()
    {
        return $this->account;
    }

    /**
     * Create an instance of the account
     *
     * @param  User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->proxyStripeExceptions(function () use ($user) {
            $this->user    = $user;
            $this->account = $this->exists()
                ? $this->retrieve()
                : $this->create();
        });
    }

    /**
     * Save the account with Stripe
     *
     * @return self
     */
    public function save()
    {
        return $this->proxyStripeExceptions(function () {
            $this->account->save();
            return $this->account;
        });
    }

    /**
     * Check if a users billing account exists
     *
     * @return Boolean
     */
    protected function exists()
    {
        return $this->user->billing_id !== null;
    }

    /**
     * Create an account for the tutor
     *
     * @return Account
     */
    abstract protected function create();

    /*
     * Retrieve a tutors account
     *
     * @return Account
     */
    abstract protected function retrieve();

    /**
     * Sync a users details with Stripe
     *
     * @return self
     */
    abstract public function sync();

}
