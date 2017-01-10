<?php namespace App\Repositories\Contracts;

use App\Location;

interface LocationRepositoryInterface
{

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param Location $location
     * @return bool
     */
    public function save(Location $location);

    /**
     * @param string $postcode
     *
     * @return mixed
     */
    public function getByPostcode($postcode);
}
