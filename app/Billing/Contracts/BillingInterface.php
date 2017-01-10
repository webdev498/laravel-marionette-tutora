<?php namespace App\Billing\Contracts;

use App\User;

interface BillingInterface
{

    /**
     * Retrieve a billing account for a user.
     * Transparently creates or retrieves.
     *
     * @return BillingAccountInterface;
     */
    public function account($user);

}
