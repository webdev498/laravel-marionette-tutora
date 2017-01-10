<?php

namespace App\Billing;

use Storage;
use Stripe\FileUpload;
use App\Billing\Contracts\FileInterface;

class StripeFile extends AbstractStripe implements FileInterface
{

    use AttributeProxyTrait;

    const IDENTITY_DOCUMENT = 'identity_document';

    /**
     * @var File
     */
    protected $file;

    /**
     * What attributes are visible form the proxied object?
     *
     * @var array
     */
    protected $visible = [
        'id',
    ];

    /**
     * Create an instance of the file
     *
     * @param  string $filepath
     * @param  string $purpose
     * @return void
     */
    public function __construct($filepath, $purpose)
    {
        $this->proxyStripeExceptions(function () use ($filepath, $purpose) {
            $file = fopen($filepath, 'r');

            $this->file = FileUpload::create(compact('file', 'purpose'));
        });
    }

    /**
     * Get the object to proxy on
     *
     * @return mixed
     */
    protected function getAttributeProxyObject()
    {
        return $this->file;
    }

}
