<?php namespace App\Billing;

trait ChargableTrait
{

    /**
     * Get the charge id value
     *
     * @return string
     */
    public function getChargeId()
    {
        return $this->{$this->getChargeIdName()};
    }

    /**
     * Set the charge id value
     *
     * @param  string $value
     * @return void
     */
    public function setChargeId($value)
    {
        $this->{$this->getChargeIdName()} = $value;
    }

    /**
     * Get the column name for the charge id
     *
     * @return string;
     */
    public function getChargeIdName()
    {
        return 'charge_id';
    }

    public function getChargeStatus()
    {
        return $this->{$this->getChargeStatusName()};
    }

    public function setChargeStatus($value)
    {
        $this->{$this->getChargeStatusName()} = $value;
    }

    public function getChargeStatusName()
    {
        return 'charge_status';
    }

    /**
     * Get the charge amount
     *
     * @return mixed
     */
    public function getChargeAmount()
    {
        return bcmul($this->{$this->getChargeAmountName()}, 100);
    }

    /**
     * Set the charge amount
     *
     * @param  string
     * @return mixed
     */
    public function setChargeAmount($value)
    {
        $this->{$this->getChargeAmountName()} = $value;
    }

    /**
     * Get the column name for the charge amount
     *
     * @return string
     */
    public function getChargeAmountName()
    {
        return 'amount';
    }

    /**
     * Get the charge description
     *
     * @return string
     */
    public function getChargeDescription()
    {
        return $this->{$this->getChargeDescriptionName()};
    }

    /**
     * Set the charge description
     *
     * @param  string
     * @return void
     */
    public function setChargeDescription($value)
    {
        $this->{$this->getChargeDescriptionName()} = $value;
    }

    /**
     * Get the column name for the description
     *
     * @return string
     */
    public function getChargeDescriptionName()
    {
        return 'description';
    }

    public function getIdempotencyKey()
    {
        return str_uuid(true);
    }
}
