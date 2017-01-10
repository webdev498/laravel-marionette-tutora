<?php namespace App\Search;

use App\Geocode\Location;
use Illuminate\Support\Collection;
use App\Commands\Jobs\FindTutorJobsCommand;
use App\Pagination\LengthAwarePaginator;

class JobsResult
{

    /**
     * @var FindTutorJobsCommand
     */
    protected $command;

    /**
     * @var Collection
     */
    protected $subjects;

    /**
     * @var Location
     */
    protected $location;

    /**
     * @var LengthAwarePaginator
     */
    protected $jobs;

    /**
     * Create an instance of results
     *
     * @param  FindTutorJobsCommand $command
     * @param  LengthAwarePaginator $jobs
     */
    public function __construct(
        FindTutorJobsCommand $command,
        LengthAwarePaginator $jobs = null
    ) {
        $this->command = $command;
        $this->jobs    = $jobs;

    }

    public function __get($key)
    {
        return $this->{$key};
    }

}
