<?php namespace App\Billing;

use App\User;
use Stripe\Account;
use App\IdentityDocument;
use App\Billing\Contracts\UserAccountInterface;
use App\Billing\Contracts\ManagedAccountInterface;

class StripeManagedAccount extends AbstractStripeAccount implements
    ManagedAccountInterface,
    UserAccountInterface
{

    /**
     * Create an account for the user
     *
     * @return Account
     */
    protected function create()
    {
        return $this->proxyStripeExceptions(function () {
            return $this->account = Account::create([
                'managed' => true,
                'country' => 'GB',
                'email'   => $this->user->email,
                'tos_acceptance' => [
                    'date' => time(),
                    'ip'   => app('request')->ip(),
                ],
                'debit_negative_balances' => true,          // try to pull funds from bank account?
            ]);
        });
    }

    /**
     * Retrieve a users account
     *
     * @return Account
     */
    protected function retrieve()
    {
        return $this->proxyStripeExceptions(function () {
            return $this->account = Account::retrieve($this->user->billing_id);
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
            // url
            $businessUrl = route('tutor.profile.show', [
                'uuid' => $this->user->uuid
            ]);
            if ($account->business_url !== $businessUrl) {
                $sync = true;
                $account->business_url = $businessUrl;
            }
            // Legal entity
            $entity = $account->legal_entity;
            // Type
            $type = 'individual';
            if ($entity->type !== $type) {
                $sync = true;
                $entity->type = $type;
            }
            // Verified details
            if ( ! $this->user->identityDocument || $this->user->identityDocument->status !== 'verified') {
                // First name
                if ($this->user->legal_first_name && $entity->first_name !== $this->user->legal_first_name) {
                    $sync = true;
                    $entity->first_name = $this->user->legal_first_name;
                }
                // Last name
                if ($this->user->legal_last_name && $entity->last_name !== $this->user->legal_last_name) {
                    $sync = true;
                    $entity->last_name = $this->user->legal_last_name;
                }
                // DOB
                if ($this->user->dob !== null) {
                    $dob = $entity->dob;
                    // Day
                    if ($dob->day !== $this->user->dob->day) {
                        $sync = true;
                        $dob->day   = $this->user->dob->day;
                    }
                    // Month
                    if ($dob->month !== $this->user->dob->month) {
                        $sync = true;
                        $dob->month = $this->user->dob->month;
                    }
                    // Year
                    if ($dob->year !== $this->user->dob->year) {
                        $sync = true;
                        $dob->year  = $this->user->dob->year;
                    }
                }
            }
            // Address
            if ($this->user->addresses->billing !== null) {
                $address         = $entity->address;
                $personalAddress = $entity->personal_address;

                $billing = $this->user->addresses->billing;
                // Line 1
                if ($address->line1 !== $billing->line_1) {
                    $sync = true;
                    $address->line1 = $personalAddress->line1 = $billing->line_1;
                }
                // Line 2
                if ($address->city !== $billing->line_2) {
                    $sync = true;
                    $address->city = $personalAddress->city = $billing->line_2;
                }
                // Skip line 3
                // Postcode
                if ($address->postal_code !== $billing->postcode) {
                    $sync = true;
                    $address->postal_code = $personalAddress->postal_code = $billing->postcode;
                }
                // Country
                $country = 'GB';
                if ($address->country !== $country) {
                    $sync = true;
                    $address->country = $personalAddress->country = $country;
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

    /**
     * Send a file off for identification
     *
     * @param  IdentityDocument $identityDocument
     * @return FileInterface
     */
    public function identityDocument(IdentityDocument $identityDocument)
    {
        return $this->proxyStripeExceptions(function () use ($identityDocument) {
            // File
            $file = new StripeFile(
                $identityDocument->path,
                StripeFile::IDENTITY_DOCUMENT
            );
            // Update
            $this->account
                ->legal_entity
                ->verification
                ->document = $file->id;
            // Save
            $this->save();
            // Return
            return $file;
        });
    }

}
