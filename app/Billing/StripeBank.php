<?php namespace App\Billing;

use Stripe\Account;
use App\Billing\Contracts\BankInterface;
use App\Billing\Contracts\ManagedAccountInterface;

class StripeBank extends AbstractStripe implements BankInterface
{

    use AttributeProxyTrait;

    /**
     * @var ManagedAccountInterface
     */
    protected $account;

    /**
     * @var Bank
     */
    protected $bank;

    /**
     * What attributes are visible form the proxied object?
     *
     * @var array
     */
    protected $visible = [
        'last_four',
    ];

    /**
     * @param  ManagedAccountInterface $account
     * @return void
     */
    public function __construct(ManagedAccountInterface $account)
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
        return $this->bank;
    }

    public function create($token)
    {
        return $this->proxyStripeExceptions(function () use ($token) {
            

            $this->bank = $this->account->external_accounts->create([
                'external_account' => $token,
            ]);

            $this->updateBankAccountToDefault();

            $this->deletePreviousBankAccounts();

            return true;
        });
    }

    protected function updateBankAccountToDefault()
    {
        $bank_account = $this->account->external_accounts->retrieve($this->bank->id);
        $bank_account->default_for_currency = true;
        $bank_account->save();
    }

    protected function deletePreviousBankAccounts()
    {
        return $this->proxyStripeExceptions(function () {

            
            if ($this->account->external_accounts->total_count > 0) {
                $accounts = $this->account->external_accounts->all();

                foreach ($accounts->data as $bank) {
                    if ($bank->id !== $this->bank->id) {
                        $bank->delete();
                    }
                }
            }
        });
    }

    public function getLastFourAttribute()
    {
        return isset($this->bank) ? $this->bank->last4 : null;
    }

}

