<?php namespace App\Commands\Jobs;

use App\Location;
use App\Commands\Command;
use Illuminate\Support\Collection;

class FindJobsCommand extends Command
{

    /**
     * Find jobs command instance.
     *
     * @param Collection $subjects
     * @param Location   $location
     * @param string     $page
     * @param string     $sort
     */
    public function __construct(
        Collection $subjects,
        Location   $location,
        $page = null,
        $sort = null
    ) {
        $this->subjects = $subjects;
        $this->location = $location;
        $this->page     = $page;
        $this->sort     = $sort;
    }

}
