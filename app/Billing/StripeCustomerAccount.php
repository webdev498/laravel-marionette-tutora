<?php namespace App\Billing;

use App\Student;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\AttachedObject;
use App\Billing\Contracts\UserAccountInterface;
use App\Billing\Contracts\CustomerAccountInterface;

class StripeCustomerAccount extends AbstractStripeAccount implements
    CustomerAccountInterface,
    UserAccountInterface
{

    /**
     * Create an account for the tutor
     *
     * @return Account
     */
    protected function create()
    {
        return $this->proxyStripeExceptions(function () {
            return Customer::create([
                'email' => $this->user->email,
                'metadata' => [
                    'first_name' => $this->user->first_name,
                    'last_name'  => $this->user->last_name,
                ],
            ]);
        });
    }

    /**
     * Retrieve a tutors account
     *
     * @return Account
     */
    protected function retrieve()
    {
        return $this->proxyStripeExceptions(function () {
            return Customer::retrieve($this->user->billing_id);
        });
    }

    /**
     * Sync a users details with Stripe
     *
     * @return self
     */
    public function sync()
    {
        return $this->proxyStripeExceptions(function () {
            // Sync?
            $sync = false;
            // Account
            $account = $this->account;
            // Log
            loginfo("[ Billing ] Might be syncing {$account->id}");
            // Email
            if ($account->email !== $this->user->email) {
                $sync = true;
                $account->email = $this->user->email;
            }
            // Metadata
            if ($account->metadata instanceof AttachedObject) {
                // First name
                if ($account->metadata->first_name !== $this->user->first_name) {
                    $sync = true;
                    $account->metadata->first_name = $this->user->first_name;
                }
                // Last name
                if ($account->metadata->last_name !== $this->user->last_name) {
                    $sync = true;
                    $account->metadata->last_name = $this->user->last_name;
                }
            }
            // So, sync?
            if ($sync === true) {
                loginfo("[ Billing ] Syncing {$account->id}");
                return $this->save();
            } else {
                loginfo("[ Billing ] Not syncing {$account->id}");
                return $this->account;
            }

        });
    }
}
