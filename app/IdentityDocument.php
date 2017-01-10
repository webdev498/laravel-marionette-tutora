<?php

namespace App;

use App\Database\Model;
use App\Events\IdentityDocumentWasSent;
use App\Billing\Contracts\FileInterface;
use App\Events\IdentityDocumentWasStored;
use App\Events\IdentityDocumentWasInspected;

class IdentityDocument extends Model
{

    /**
     * The extension of the filetype we'll
     * convert the documents to.
     */
    const EXT = 'jpg';

    protected $hidden = [
        'billing_id',
    ];

    /**
     * The attributes to append to the model's array and JSON form.
     *
     * @var array
     */
    protected $appends = [
        'path',
        'src',
    ];

    /**
     * An identity document belongs to a user
     *
     * @return Belongsto
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Create an entry for an identity document
     *
     * @param  string $uuid
     * @return IdentityDocument
     */
    public static function store($uuid)
    {
        // Create
        $identityDocument = new static();
        // Attributes
        $identityDocument->uuid    = $uuid;
        $identityDocument->ext     = static::EXT;
        $identityDocument->status  = 'stored';
        $identityDocument->details = null;
        // Raise
        $identityDocument->raise(new IdentityDocumentWasStored($identityDocument));
        // Return
        return $identityDocument;
    }

    /**
     * Re-store an IdentityDocument
     *
     * @param  IdentityDocument $identityDocument
     * @return IdentityDocument
     */
    public static function restore(IdentityDocument $identityDocument)
    {
        // Attributes
        $identityDocument->status  = 'stored';
        $identityDocument->details = null;
        // Raise
        $identityDocument->raise(new IdentityDocumentWasStored($identityDocument));
        // Return
        return $identityDocument;
    }

    /**
     * The identification document has been sent to billing
     *
     * @param  IdentityDocument $identityDocument
     * @param  FileInterface    $file
     * @return IdentityDocument
     */
    public static function sent(
        IdentityDocument $identityDocument,
        FileInterface    $file
    ) {
        // Attributes
        $identityDocument->status     = 'sent';
        $identityDocument->details    = null;
        $identityDocument->billing_id = $file->id;
        // Raise
        $identityDocument->raise(new IdentityDocumentWasSent($identityDocument));
        // Return
        return $identityDocument;
    }

    public static function inspected(
        IdentityDocument $identityDocument,
        $status,
        $details
    ) {
        $identityDocument->status  = $status;
        $identityDocument->details = $details;

        $identityDocument->raise(new IdentityDocumentWasInspected($identityDocument));

        return $identityDocument;
    }

    /**
     * Mutate the path attribute.
     *
     * @return string
     */
    protected function getPathAttribute()
    {
        return sprintf(
            '%s/app/identity-documents/%s.%s',
            storage_path(),
            $this->uuid,
            $this->ext
        );
    }

    /**
     * Mutate the src attribute.
     *
     * @return string
     */
    protected function getSrcAttribute()
    {
        return route('identity-document.show', [
            'uuid' => $this->uuid,
            'ext'  => $this->ext,
        ]);
    }

    public static function refresh(IdentityDocument $identityDocument, $billingId)
    {
        $identityDocument->billing_id = $billingId;
        $identityDocument->status     = 'pending';
        $identityDocument->details    = null;

        return $identityDocument;
    }

}
