<?php namespace App\Commands;

class UpdateUserCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        $uuid,
        $first_name       = null,
        $last_name        = null,
        $legal_first_name = null,
        $legal_last_name  = null,
        $dob              = null,
        $email            = null,
        $telephone        = null,
        $password         = null,
        $bank             = null,
        $card             = null,
        $status           = null,
        $reset_password   = null,
        $op               = null,
        $confirm_reset_password = null,
        Array $identity_document = null,
        Array $addresses         = null,
        Array $profile           = null,
        Array $settings          = null
    ) {
        $this->uuid              = $uuid;
        $this->first_name        = $first_name;
        $this->last_name         = $last_name;
        $this->legal_first_name  = $legal_first_name;
        $this->legal_last_name   = $legal_last_name;
        $this->dob               = $dob;
        $this->email             = $email;
        $this->telephone         = $telephone;
        $this->password          = $password;
        $this->bank              = $bank;
        $this->card              = $card;
        $this->status            = $status;

        $this->reset_password    = $reset_password;
        $this->confirm_reset_password = $confirm_reset_password;
        $this->op                = $op;

        $this->identity_document = $identity_document;
        $this->addresses         = $addresses;
        $this->profile           = $profile;
        $this->settings          = $settings;
    }

}
