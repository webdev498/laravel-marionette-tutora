<?php namespace App\Tutor;

use Request;
use App\Geocode\Location;
use Illuminate\Support\Collection;
use App\Pagination\SearchPaginator;
use App\Repositories\EloquentTutorRepository;

class Finder
{
    protected $tutors;

    protected $paginator;

    public function __construct(
        EloquentTutorRepository $tutors,
        SearchPaginator         $paginator
    ) {
        $this->tutors    = $tutors;
        $this->paginator = $paginator;
    }

    public function byBestMatch(Collection $subjects, Location $location)
    {
        return $this->tutors->findAllRankedByBestMatch($subjects, $location);
    }

    public function pageByBestMatch($page, Collection $subjects, Location $location)
    {
        $items = $this->tutors->findPageRankedByBestMatch($page, $subjects, $location);

        $count  = $this->tutors->countByBestMatch($subjects, $location);

        return $this->paginator->paginate($items, $count, $page, [
            'path' => Request::url(),
        ]);
    }

}
