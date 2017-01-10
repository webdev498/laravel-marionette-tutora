<?php

namespace App\Repositories;

use DateTime;
use App\Geocode\Location;
use App\Search;
use App\Subject;
use App\Student;
use App\Relationship;
use App\Repositories\Contracts\SearchRepositoryInterface;


class EloquentSearchRepository implements SearchRepositoryInterface 
{
	/**
     * @var Search
     */

	protected $search;

	public function __construct(Search $search)
	{
		$this->search = $search;
	}

	public function getById($id)
	{
		return $this->search->find($id);
	}

	public function save(Search $search)
	{	
		return $search->save();
	}

	public function saveForStudent(Search $search, Student $student)
	{
		$search->students()->attach($student->id);
		return $search;
	}

	public function saveForRelationship(Search $search, Relationship $relationship)
	{
		$search->relationships()->attach($relationship->id);
	}

	public function countBetweenDates(DateTime $start, DateTime $end)
	{
		return $this->search
			->whereBetween('created_at', [$start, $end])
			->count();
	}
}

