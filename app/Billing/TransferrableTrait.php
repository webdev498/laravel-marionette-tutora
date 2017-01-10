<?php namespace App\Billing;

trait TransferrableTrait
{

    /**
     * Set the charge id value
     *
     * @param  string $value
     * @return void
     */
    public function setTransferStatus($value)
    {
        $this->{$this->getTransferStatusName()} = $value;
    }

    public function getTransferStatusName()
    {
        return 'transfer_status';
    }

}