<?php namespace App\Handlers\Commands;

use App\Commands\SearchResultsForMarketingCommand;
use App\Geocode\Location;
use App\Pagination\SearchPaginator;
use App\Repositories\Contracts\SubjectRepositoryInterface;
use App\Search\LocationSearcher;
use App\Search\Results;
use App\Search\TutorSearcher;
use Illuminate\Support\Collection;

class SearchResultsForMarketingCommandHandler extends CommandHandler
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
     * @param  SubjectRepositoryInterface  $subjects
     * @param  LocationSearcher $locations
     * @param  TutorSearcher    $tutors
     * @param  SearchPaginator  $paginator
     * @return void
     */
    public function __construct(
        SubjectRepositoryInterface   $subjects,
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
    public function handle(SearchResultsForMarketingCommand $command)
    {
        
        $subjects = $this->subjects($command);
        $location = $this->location($command);
        $tutors   = $this->tutors($command, $subjects, $location);
        
        return $tutors;
    }

    protected function subjects($command)
    {
        if (! $command->search ) {
            return null;
        }

        if (! $command->search->subject_id) {
            return null;
        }

        return $this->subjects->getDescendantsById($command->search->subject_id);
    }

    protected function location($command)
    {
        if (! $command->search) {
            return null;
        }

        if ($command->search->latitude === null && $command->search->latitude === null) {
            return null;
        }

        return new \App\Geocode\Location([
            'latitude'  => $command->search->latitude,
            'longitude'  => $command->search->longitude
            ]);
    }

    protected function tutors($command, $subjects, $location)
    {
        $page    = (int) 1;
        $perPage = $command->selection;
        $sort    = TutorSearcher::SORT_BEST;

        list($items, $count) = $this->tutors->search(
            $page,
            $perPage,
            $sort,
            $subjects,
            $location
        );

        if ($items->count() >= $command->count) {
            return $items->random($command->count);
        } else {
            return $items;
        }


    }
}
