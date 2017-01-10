<?php namespace App\Search;

use App\Geocode\Location;
use Illuminate\Support\Collection;
use App\Commands\SearchResultsCommand;
use App\Pagination\LengthAwarePaginator;

class Results
{

    /**
     * @var SearchResultsCommand
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
    protected $tutors;

     /**
     * @var Number of reviews
     */
    protected $numberOfReviews;   

     /**
     * @var Average review rating
     */
    protected $averageReview;
    /**
     * Create an instance of results
     *
     * @param  Collection           $subjects
     * @param  Location             $location
     * @param  LengthAwarePaginator $tutors
     */
    public function __construct(
        SearchResultsCommand $command,
        Collection           $subjects = null,
        Location             $location = null,
        LengthAwarePaginator $tutors   = null,
        $numberOfReviews = null,
        $averageReview = null
    ) {
        $this->command  = $command;
        $this->subjects = $subjects;
        $this->location = $location;
        $this->tutors   = $tutors;
        $this->numberOfReviews  = $numberOfReviews;
        $this->averageReview  = $averageReview;

    }

    public function __get($key)
    {
        return $this->{$key};
    }

}
