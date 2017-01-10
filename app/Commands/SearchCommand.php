<?php namespace App\Commands;

class SearchCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        $subject,
        $location
    ) {
        $this->subject     = $subject;
        $this->location    = $location;
    }

}
