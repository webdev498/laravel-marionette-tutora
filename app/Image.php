<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Observers\ImageObserver;

class Image extends Model
{

    /**
     * Boot the model
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        parent::observe(app(ImageObserver::class));
    }

    /**
     * Create an image instance.
     *
     * @param  String $uuid
     * @param  String $storage_hash
     * @param  String $extension
     * @param  String $originalName
     * @param  String $type
     * @param  String $size
     *
     * @return Image
     *
     */
    public static function make(
        $uuid,
        $storage_hash,
        $extension,
        $originalName,
        $type,
        $size
    ) {
        // New
        $image = new static();

        // Attributes
        $image->uuid         = $uuid;
        $image->storage_hash = $storage_hash;
        $image->extension    = $extension;
        $image->originalName = $originalName;
        $image->size         = $size;
        $image->type         = $type;

        // Return
        return $image;
    }
}
