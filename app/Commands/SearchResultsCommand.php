<?php namespace App\Commands;

class SearchResultsCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        $subject = null,
        $location = null,
        $page = null,
        $sort = null
    ) {
        $this->subject   = $subject;
        $this->location  = $location;
        $this->page      = $page;
        $this->sort      = $sort;
    }

}
