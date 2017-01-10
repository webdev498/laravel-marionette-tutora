<?php namespace App\Handlers\Commands;

use App\Admin;
use App\Tutor;
use App\Commands\SearchCommand;
use App\Repositories\Contracts\SearchRepositoryInterface;
use App\Search;
use App\Search\LocationSearcher;
use App\Search\Results;
use App\Search\SubjectSearcher;
use App\Student;
use Illuminate\Support\Facades\Auth;

class SearchCommandHandler extends CommandHandler
{

    /**
     * @var SubjectSearcher
     */
    protected $subjectSearcher;

    /**
     * @var LocationSearcher
     */
    protected $locationSearcher;
    
    /**
     * @var SearchRepositoryInterface
     */
    protected $searches;
    
    /**
     * Create a new handler instance.
     *
     * @param  SubjectSearcher  $subjectSearcher
     * @param  LocationSearcher $locationSearcher
     * @return void
     */
    public function __construct(
        SubjectSearcher  $subjectSearcher,
        LocationSearcher $locationSearcher,
        SearchRepositoryInterface $searches
    ) {
        $this->subjectSearcher  = $subjectSearcher;
        $this->locationSearcher = $locationSearcher;
        $this->searches         = $searches;
    }

    /**
     * Execute the command.
     *
     * @param  SearchCommand $command
     * @return Results
     */
    public function handle(SearchCommand $command)
    {
        $subjects = $this->subjectSearcher->search($command->subject);
        $location = $this->locationSearcher->search($command->location);
        
        $search = $this->saveSearch($subjects, $location);

         // dd($command);
        // dd($location);

        session(['location' => $location]);
        session(['subject' => $subjects ? $subjects->first() : null]);
        session(['search_id' => $search->id]);
        
        return [$subjects, $location];
    }

    public function saveSearch($subjects, $location)
    {

        $subjects = $subjects ? $subjects->first() : null;

        $search = Search::make($location, $subjects);
        

        if (!(Auth::user() instanceof Tutor) && !(Auth::user() instanceof Admin)) {
            $this->searches->save($search);
        }

        if (Auth::user() instanceof Student) {
            $this->searches->saveForStudent($search, Auth::user());
        } 

        return $search;
    }

}
