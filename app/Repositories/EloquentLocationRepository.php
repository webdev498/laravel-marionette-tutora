<?php

namespace App\Repositories;

use DateTime;
use App\Location;
use App\Search;
use App\User;
use App\Subject;
use App\Student;
use App\Relationship;
use App\Repositories\Contracts\LocationRepositoryInterface;


class EloquentLocationRepository implements LocationRepositoryInterface
{
	/**
     * @var Location
     */
	protected $location;

	public function __construct(Location $location)
	{
		$this->location = $location;
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function getById($id)
	{
		return $this->location->find($id);
	}

	/**
	 * @param string $postcode
	 *
	 * @return mixed
	 */
	public function getByPostcode($postcode)
	{
		return $this->location->where('postcode', $postcode)->first();
	}

	/**
	 * @param Location $location
	 * @return bool
	 */
	public function save(Location $location)
	{	
		return $location->save();
	}
}
