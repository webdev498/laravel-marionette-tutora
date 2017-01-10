<?php namespace App\Billing;

use Stripe\Transfer;
use App\Billing\Contracts\TransferInterface;

class StripeTransfer extends AbstractStripe implements TransferInterface
{
	public function retrieve ($id)
	{
		return Transfer::retrieve($id);
	}
}