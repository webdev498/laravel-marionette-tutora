<?php namespace App\Commands\Jobs;

use App\Tutor;
use App\Address;
use App\Location;
use App\Commands\Command;

class FindTutorJobsCountCommand extends Command
{

    /**
     * Find jobs command instance.
     *
     * @param Tutor  $tutor
     */
    public function __construct(
        Tutor $tutor
    ) {
        $address  = $tutor->addresses()->where('name', Address::NORMAL)->first();
        $location = Location::makeFromAddress($address);

        $this->tutor    = $tutor;
        $this->location = $location;
    }

}
