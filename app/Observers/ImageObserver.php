<?php namespace App\Observers;

use App\Image;
use App\FileHandlers\Image\ImageUploader;

class ImageObserver
{

    /**
     * @var ImageUploader
     */
    protected $uploader;

    /**
     * Create an instance of the observer.
     *
     * @param ImageUploader $uploader
     */
    public function __construct(
        ImageUploader $uploader
    )
    {
        $this->uploader = $uploader;
    }

    public function deleting(Image $image)
    {
        $this->uploader->removeImage($image);
    }

}
