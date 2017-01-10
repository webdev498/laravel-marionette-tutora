<?php

namespace App\Repositories;

use App\Relationship;
use DateTime;
use App\Job;
use App\Search;
use App\Tutor;
use App\Student;
use App\Location;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\DatabaseManager as Database;
use App\Repositories\Contracts\JobRepositoryInterface;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class EloquentJobRepository extends AbstractEloquentLocationRepository implements JobRepositoryInterface
{
	const RADIUS = 10;

	/**
     * @var Job
     */
	protected $job;

	/**
	 * @param Job 		$job
	 * @param Database 	$database
	 */
	public function __construct(
		Job      $job,
		Database $database
	)
	{
		$this->job = $job;

		parent::__construct($database);
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function getById($id)
	{
		return $this->job->find($id);
	}

	/**
	 * @param $uuid
	 *
	 * @return mixed
	 */
	public function findByUuid($uuid)
	{
		return $this->job->where('uuid', $uuid)->first();
	}

	/**
	 * Get the relationships the given tutor belongs to.
	 * Results are paginated and sorted by the students first name.
	 *
	 * @param  Student $student
	 * @param  Integer $page
	 * @param  Integer $perPage
	 *
	 * @return Collection
	 */
	public function getByStudent(Student $student, $page, $perPage)
	{
		return $student
			->jobs()
			->takePage($page, $perPage)
			->with(array_extend([
				'locations'
			], $this->with))
			->get();
	}

	/**
	 * @param  Student $student
	 * @param  Tutor   $tutor
	 *
	 * @return Collection
	 */
	public function getByTutorAndStudent(Student $student, Tutor $tutor)
	{
		/* @var HasMany */
		$query = $student->jobs();

		$query = $query
			->leftJoin('tuition_job_message_line', 'tuition_jobs.id', '=', 'tuition_job_message_line.job_id')
			->leftJoin('message_lines', 'message_lines.id', '=', 'tuition_job_message_line.message_line_id')
			->leftJoin('messages', 'messages.id', '=', 'message_lines.message_id')
			->leftJoin('relationships', 'relationships.id', '=', 'messages.relationship_id');

		$query = $query
			->where('relationships.student_id', '=', $student->id)
			->where('relationships.tutor_id', '=', $tutor->id)
			->where('relationships.status', '!=', Relationship::REQUESTED_BY_TUTOR)
			->where('tuition_jobs.status', '=', Job::STATUS_LIVE);

		return $query->get(['tuition_jobs.*']);
	}

	/**
	 * @param Job $job
	 *
	 * @return bool
	 */
	public function save(Job $job)
	{
		return $job->save();
	}

	/**
	 * @param Job $job
	 *
	 * @return bool
	 */
	public function delete(Job $job)
	{
		$job->locations()->detach();

		return $job->delete();
	}

	/**
	 * Get messages
	 *
	 * @param  string $status
	 *
	 * @return Collection
	 */
	public function get($status = null)
	{
		$query = $this->job
			->newQuery()
			->takePage($this->page, $this->perPage)
			->orderBy('updated_at', 'desc')
			->with($this->with);

		if ($status) {
			$query->where('status', '=', $status);
		}

		return $query->get();
	}

	/**
	 * Count the messages
	 *
	 * @param  string $status
	 *
	 * @return integer
	 */
	public function count($status = null)
	{
		$query = $this->job
			->newQuery();

		if ($status) {
			$query->where('status', '=', $status);
		}

		return $query->count();
	}

	/**
	 * @param \DateTime $date
	 *
	 * @return Collection
	 */
	public function getOpenedBeforeDate(\DateTime $date)
	{
		$query = $this->job;

		$query = $query->where('status', '=', Job::STATUS_LIVE);
		$query = $query->where('opened_at', '<', $date);

		return $query->get();
	}


	// SEARCH ///////////////////////////////////////////////////////////

	/**
	 * @param Collection|null $subjects
	 * @param Location|null $location
	 * @param array $favouritedBy
	 * @param $page
	 * @param $perPage
	 * @param Carbon|null $afterDate
	 * @param integer $notAppliedBy
	 *
	 * @return mixed
	 */
	public function getByDistance(
		Collection $subjects = null,
		Location   $location = null,
		$favouritedBy = [],
		$page = null,
		$perPage = null,
		Carbon $afterDate = null,
		$notAppliedBy = false
	) {
		$query = $this->bySubjectsAndLocation($subjects, $location);

		if($notAppliedBy) {
			$query = $this->addNotAppliedBy($query, $notAppliedBy);
		}

		$query = $this->addWhereFavouritedBy($query, $favouritedBy);

		$query = $query->orderBy('distance', 'asc');

		$query = $this->addPagination($query, $page, $perPage);

		$query = $this->addAfterDate($query, $afterDate);

		$results = $query->get();

		return $results;
	}

	/**
	 * @param Collection|null $subjects
	 * @param Location|null $location
	 * @param array $favouritedBy
	 * @param $page
	 * @param $perPage
	 * @param Carbon|null $afterDate
	 * @param integer $notAppliedBy
	 *
	 * @return mixed
	 */
	public function getByDateCreated(
		Collection $subjects = null,
		Location   $location = null,
		$favouritedBy = [],
		$page = null,
		$perPage = null,
		Carbon $afterDate = null,
		$notAppliedBy = null
	) {
		$query = $this->bySubjectsAndLocation($subjects, $location);

		if($notAppliedBy) {
			$query = $this->addNotAppliedBy($query, $notAppliedBy);
		}

		$query = $this->addWhereFavouritedBy($query, $favouritedBy);

		$query = $query->orderBy('tuition_jobs.opened_at', 'desc');

		$query = $this->addPagination($query, $page, $perPage);

		$query = $this->addAfterDate($query, $afterDate);

		$results = $query->get();

		return $results;
	}

	/**
	 * @param Collection|null $subjects
	 * @param Location|null $location
	 * @param array $favouritedBy
	 * @param Carbon|null $afterDate
	 * @param integer $notAppliedBy
	 *
	 * @return int
	 */
	public function countByDistance(
		Collection $subjects = null,
		Location   $location = null,
		$favouritedBy = [],
		$afterDate = null,
		$notAppliedBy = null
	) {
		return $this->countBySubjectsAndLocation($subjects, $location, $favouritedBy, $afterDate, $notAppliedBy);
	}

	/**
	 * @param Collection|null $subjects
	 * @param Location|null $location
	 * @param array $favouritedBy
	 * @param Carbon|null $afterDate
	 * @param integer $notAppliedBy
	 *
	 * @return int
	 */
	public function countByDateCreated(
		Collection $subjects = null,
		Location   $location = null,
		$favouritedBy = [],
		$afterDate = null,
		$notAppliedBy = null
	) {
		return $this->countBySubjectsAndLocation($subjects, $location, $favouritedBy, $afterDate, $notAppliedBy);
	}

	/**
	 * @param Collection|null $subjects
	 * @param Location|null $location
	 * @param array $favouritedBy
	 * @param Carbon|null $afterDate
	 * @param integer $notAppliedBy
	 *
	 * @return int
	 */
	protected function countBySubjectsAndLocation(
		Collection $subjects = null,
		Location   $location = null,
		$favouritedBy = [],
		Carbon $afterDate = null,
		$notAppliedBy = null
	) {
		$query = $this->bySubjectsAndLocation($subjects, $location);

		if($notAppliedBy) {
			$query = $this->addNotAppliedBy($query, $notAppliedBy);
		}

		$query = $this->addWhereFavouritedBy($query, $favouritedBy);

		$query = $this->addAfterDate($query, $afterDate);

		$result = $query->count();

		return $result ? $result : 0;
	}

	/**
	 * @param Collection|null $subjects
	 * @param Location|null $location
	 *
	 * @return mixed
	 */
	protected function bySubjectsAndLocation(
		Collection $subjects = null,
		Location   $location = null
	) {
		$query = $this->job
			->live()
			->select('tuition_jobs.*')
			->with('tutors');

		if ($subjects !== null) {
			$query = $this->addWhereSubjects($query, $subjects);
		}

		if ($location !== null) {
			$query = $this->addWhereLocation($query, $location);
		}

		return $query;
	}

	/**
	 * @param $query
	 * @param integer $tutorId
	 *
	 * @return mixed
	 */
	protected function addNotAppliedBy($query, $tutorId)
	{
		return $query
			->whereDoesntHave('tutors', function ($query) use ($tutorId) {
				$query->where('user_id', '=', $tutorId);

				$query->where(function($query) use ($tutorId) {
					$query->where('applied', true);
					$query->orWhere('created_for_tutor', true);
				});
			});
	}

	protected function addWhereSubjects($query, $subjects)
	{
		return $query
			->whereHas('subject', function ($query) use ($subjects) {
				$query->whereIn('id', $subjects->lists('id'));
			});
	}

	/**
	 * @param $query
	 * @param array $favouritedBy
	 *
	 * @return mixed
	 */
	protected function addWhereFavouritedBy($query, $favouritedBy = [])
	{
		if(!$favouritedBy) {return $query;}

		return $query
			->whereHas('tutors', function ($query) use ($favouritedBy) {
				$query->whereIn('user_id', $favouritedBy);
				$query->where('favourite', true);
			});
	}

	/**
	 * @param $query
	 * @param null $page
	 * @param null $perPage
	 *
	 * @return mixed
	 */
	protected function addPagination($query, $page = null, $perPage = null)
	{
		if($perPage) {
			$query = $query->take($perPage);
		}
		if($page && $perPage) {
			$query = $query->skip(($page - 1) * $perPage);
		}

		return $query;
	}

	/**
	 * @param $query
	 * @param Carbon|null $afterDate
	 *
	 * @return mixed
	 */
	protected function addAfterDate($query, $afterDate = null)
	{
		if($afterDate) {
			$query = $query->where('opened_at', '>=', $afterDate);
		}

		return $query;
	}
}
