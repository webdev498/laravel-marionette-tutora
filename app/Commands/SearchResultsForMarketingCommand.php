<?php namespace App\Commands;

class SearchResultsForMarketingCommand extends Command
{


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        $search   = null,
        $count    = 3,
        $selection = 10
    ) {
        $this->search = $search;
        $this->count     = $count;
        $this->selection     = $selection;

    }

}
