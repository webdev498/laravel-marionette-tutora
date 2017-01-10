<?php namespace App\Commands\Jobs;

use App\Tutor;
use App\Address;
use App\Location;
use App\Commands\Command;

class FindTutorJobsCommand extends Command
{

    /**
     * Find jobs command instance.
     *
     * @param Tutor  $tutor
     * @param string $page
     * @param string $sort
     * @param string $filter
     */
    public function __construct(
        Tutor $tutor,
        $page = null,
        $sort = null,
        $filter = null
    ) {
        $address  = $tutor->addresses()->where('name', Address::NORMAL)->first();
        $location = Location::makeFromAddress($address);

        $this->tutor    = $tutor;
        $this->location = $location;
        $this->page     = $page;
        $this->sort     = $sort;
        $this->filter   = $filter;
    }

}
