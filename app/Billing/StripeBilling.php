<?php namespace App\Billing;

use App\User;
use App\Tutor;
use Stripe\Stripe;
use Stripe\FileUpload;
use Illuminate\Http\Request;
use InvalidArgumentException;
use App\Billing\Contracts\BillingInterface;
use App\Billing\Contracts\ChargableInterface;
use Illuminate\Foundation\Bus\DispatchesCommands;
use App\Billing\Contracts\ManagedAccountInterface;
use App\Billing\Contracts\CustomerAccountInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class StripeBilling extends AbstractStripe implements BillingInterface
{

    use DispatchesCommands;

    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * A cache of already fetched accounts
     * @var Array
     */
    protected $accounts = [];

    /**
     * Create an instance of billing.
     *
     * @param  UserRepositoryInterface $users
     * @return void
     */
    public function __construct(
        UserRepositoryInterface $users
    ) {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $this->users = $users;
    }

    public function account($user)
    {
        if (
            $user instanceof CustomerAccountInterface ||
            $user instanceof ManagedAccountInterface
        ) {
            return $user;
        }

        if ( ! ($user instanceof User)) {
            throw new InvalidArgumentException('$user must be an instance of User');
        }

        return $this->proxyStripeExceptions(function () use ($user) {
            if (isset($this->accounts[$user->id])) {
                return $this->accounts[$user->id];
            }

            $account = $user instanceof Tutor
                ? new StripeManagedAccount($user)
                : new StripeCustomerAccount($user);

            if ($user->billing_id !== $account->id) {
                User::billing($user, $account);
                $this->users->save($user);
            }

            return $this->accounts[$user->id] = $account;
        });
    }

    public function bank(User $user)
    {
        return $this->proxyStripeExceptions(function () use ($user) {
            $account = $this->account($user);
            return new StripeBank($account);
        });
    }

    public function card(User $user)
    {
        return $this->proxyStripeExceptions(function () use ($user) {
            $account = $this->account($user);
            return new StripeCard($account);
        });
    }

    public function charge($account, ChargableInterface $product)
    {
        return $this->proxyStripeExceptions(function () use ($account, $product) {
            $account = $this->account($account);
            return new StripeCharge($account, $product);
        });
    }

    public function refund($product)
    {
        return $this->proxyStripeExceptions(function () use ($product) {
            return new StripeRefund($product);
        });
    }

    public function event()
    {
        return $this->proxyStripeExceptions(function () {
            return new StripeEvent();
        });
    }

    public function file($file)
    {
        return $this->proxyStripeExceptions(function () use ($file) {
            return FileUpload::create([
                'purpose' => 'identity_document',
                'file'    => $file,
            ]);
        });
    }

    public function transfer($id)
    {
        return $this->proxyStripeExceptions(function () use ($id) {
            $transfer = new StripeTransfer();
            return $transfer = $transfer->retrieve($id);
        });
    }

    public function balanceTransaction()
    {
        return $this->proxyStripeExceptions(function () {
            return $balanceTransaction = new StripeBalanceTransaction();
        });
    }

}
