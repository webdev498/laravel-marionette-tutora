<?php namespace App\Handlers\Commands;

use App\Search\Results;
use App\Geocode\Location;
use App\Search\TutorSearcher;
use App\Search\SubjectSearcher;
use App\Search\LocationSearcher;
use Illuminate\Support\Collection;
use App\Pagination\SearchPaginator;
use App\Commands\SearchResultsCommand;

class SearchResultsCommandHandler extends CommandHandler
{

    /**
     * @var SubjectSearcher
     */
    protected $subjects;

    /**
     * @var LocationSearcher
     */
    protected $locations;

    /**
     * @var TutorSearcher
     */
    protected $tutors;

    /**
     * @var SearchPaginator
     */
    protected $paginator;

    /**
     * Create a new handler instance.
     *
     * @param  SubjectSearcher  $subjects
     * @param  LocationSearcher $locations
     * @param  TutorSearcher    $tutors
     * @param  SearchPaginator  $paginator
     * @return void
     */
    public function __construct(
        SubjectSearcher   $subjects,
        LocationSearcher  $locations,
        TutorSearcher     $tutors,
        SearchPaginator   $paginator
    ) {
        $this->subjects  = $subjects;
        $this->locations = $locations;
        $this->tutors    = $tutors;
        $this->paginator = $paginator;
    }

    /**
     * Execute the command.
     *
     * @param  SearchResultsCommand $command
     * @return Results
     */
    public function handle(SearchResultsCommand $command)
    {
        
        $subjects = $this->subjects($command);
        $location = $this->location($command);
        $tutors   = $this->tutors($command, $subjects, $location);
        $numberOfReviews  = $this->numberOfReviews($command, $subjects, $location);
        $averageReview = $this->averageReview($command, $subjects, $location);
        
        return new Results($command, $subjects, $location, $tutors, $numberOfReviews, $averageReview);
    }

    protected function subjects($command)
    {
        if ($command->subject === null) {
            return null;
        }

        return $this->subjects->search(
            session('subject_'.$command->subject, $command->subject)
        );
    }

    protected function location($command)
    {
        if ($command->location === null) {
            return null;
        }

        return $this->locations->search(
            session('location_'.$command->location, $command->location)
        );
    }

    protected function tutors($command, $subjects, $location)
    {
        $page    = (int) ($command->page ?: 1);
        $perPage = SearchPaginator::PER_PAGE;
        $sort    = $command->sort ?: TutorSearcher::SORT_BEST;

        list($items, $count) = $this->tutors->search(
            $page,
            $perPage,
            $sort,
            $subjects,
            $location
        );


        if ($subjects instanceof Collection && $location instanceof Location) {
            $path = relroute('search.index', [
                'subject'  => $command->subject,  // from the url
                'location' => $command->location, // from the url
            ]);
        }

        if ( $subjects instanceof Collection && ! $location instanceof Location) {
                
            $path = relroute('search.subject', [
                'subject' => $command->subject,  // from the url
            ]);
        } 

        if (!($subjects instanceof Collection) && $location instanceof Location) {
            $path = relroute('search.location', [
                    'location' => $command->location,  // from the url
                ]);
        }

        if (!($subjects instanceof Collection) && !($location instanceof Location)) {
            $path = relroute('search.none');
        }

        return $this->paginator->paginate($items, $count, $page, [
            'path' => $path,
            'query' => [
                'sort' => $sort,
            ],
        ]);
    }

    protected function numberOfReviews($command, $subjects, $location)
    {
        return $this->tutors->countReviews($command, $subjects, $location);
    }
    protected function averageReview($command, $subjects, $location)
    {
        return $this->tutors->averageReview($command, $subjects, $location);
    }
}
