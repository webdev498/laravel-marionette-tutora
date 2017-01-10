<?php namespace App\Repositories;

use App\Database\Exceptions\ResourceNotPersistedException;
use App\Repositories\Contracts\ImageRepositoryInterface;
use Illuminate\Database\DatabaseManager as Database;
use Illuminate\Support\Collection;
use App\Image;

class EloquentImageRepository implements ImageRepositoryInterface
{

    /*
     * @var Database
     */
    protected $database;

    /*
     * @var Image
     */
    protected $image;

    /**
     * Create an instance of this
     *
     * @param Database $database
     * @param Image    $image
     */
    public function __construct(Database $database, Image $image)
    {
        $this->database = $database;
        $this->image    = $image;
    }

    /**
     * Persist an image to the database
     *
     * @param  Image $image
     *
     * @return Image|null
     */
    public function save(
        Image $image
    ) {

        return $this->database->transaction(function () use (
            $image
        ) {
            // Image
            if ( ! $image->save()) {
                throw new ResourceNotPersistedException();
            }

            return $image;
        });
    }

    /**
     * Find an image by id.
     *
     * @param  Integer $id
     *
     * @return Image
     */
    public function findById($id)
    {
        return $this->image
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * Generate a uuid, ensuring it is in fact unique to the image
     *
     * @return string
     */
    public function generateUuid()
    {
        do {
            $uuid = str_uuid();
        } while ($this->countByUuid($uuid) > 0);

        return $uuid;
    }

    /**
     * Find an image by uuid.
     *
     * @param  String $uuid
     *
     * @return Image
     */
    public function findByUuid($uuid)
    {
        return $this->image
            ->where('uuid', '=', $uuid)
            ->first();
    }

    /**
     * Return the number of images that have a given uuid
     *
     * @param string $uuid
     *
     * @return integer
     */
    public function countByUuid($uuid)
    {
        return $this->image
            ->whereUuid($uuid)
            ->count();
    }
}
