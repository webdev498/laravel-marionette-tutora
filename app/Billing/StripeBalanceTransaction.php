<?php namespace App\Billing;

use Stripe\BalanceTransaction;
use App\Billing\Contracts\BalanceTransactionInterface;

class StripeBalanceTransaction extends AbstractStripe implements BalanceTransactionInterface
{
	public function getByTransferAndAccount($transfer_id, $user_id)
	{
	return BalanceTransaction::all(['transfer' => $transfer_id], ['stripe_account' => $user_id]);
	}
}