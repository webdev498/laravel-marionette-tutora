<?php

namespace App\Presenters;

use App\IdentityDocument;
use League\Fractal\TransformerAbstract;

class IdentityDocumentPresenter extends TransformerAbstract
{

    /**
     * Turn this object into a generic array
     *
     * @param  IdentityDocument $identityDocument
     * @return array
     */
    public function transform(IdentityDocument $identityDocument)
    {
        return [
            'uuid'        => (string)  $identityDocument->uuid,
            'is_verified' => (boolean) $identityDocument->status === 'verified',
            'status'      => (string)  $identityDocument->status,
            'details'     => (string)  $identityDocument->details,
            'src'         => route('identity-document.show', [
                'uuid' => $identityDocument->uuid,
                'ext'  => $identityDocument->ext,
            ]),
        ];
    }

}
