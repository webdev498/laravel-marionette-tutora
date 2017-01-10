<?php namespace App\Billing;

trait AttributeProxyTrait
{

    /**
     * Get an attribute from the account
     */
    public function __get($key)
    {
        $method = 'get'.studly_case($key).'Attribute';
        $value  = null;

        if ($this->proxyAttributeIsVisible($key)) {
            if (method_exists($this, $method)) {
                $value = $this->$method();
            } elseif ($this->getAttributeProxyObject() !== null) {
                $value = $this->getAttributeProxyObject()->$key;
            }
        }

        return $value;
    }

    /**
     * Check if an attribute is visible on the proxied object
     *
     * @param  string $key
     * @return boolean
     */
    protected function proxyAttributeIsVisible($key)
    {
        if (isset($this->visible) && is_array($this->visible)) {
            return array_search($key, $this->visible) !== false;
        }

        return true;
    }

    /**
     * Get the object to proxt on
     *
     * @return mixed
     */
    protected function getAttributeProxyObject()
    {
        return null;
    }

}
