<?php namespace App\Repositories\Contracts;

use App\Image;

interface ImageRepositoryInterface
{

    /**
     * Persist an image to the database
     *
     * @param  Image $image
     *
     * @return Image|null
     */
    public function save(
        Image $image
    );

    /**
     * Find an image by id.
     *
     * @param  Integer $id
     *
     * @return Image
     */
    public function findById($id);

    /**
     * Find an image by uuid.
     *
     * @param  Integer $uuid
     *
     * @return Image
     */
    public function findByUuid($uuid);

}
