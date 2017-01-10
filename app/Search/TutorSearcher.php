<?php namespace App\Search;

use App\Repositories\Contracts\TutorRepositoryInterface;
use App\Repositories\Contracts\AddressRepositoryInterface;

class TutorSearcher extends AbstractSearcher
{

    const SORT_BEST     = 'best';
    const SORT_DISTANCE = 'distance';
    const SORT_RATING   = 'rating';

    protected $addresses;

    protected $tutors;

    public function __construct(
        AddressRepositoryInterface $addresses,
        TutorRepositoryInterface   $tutors
    ) {
        $this->addresses = $addresses;
        $this->tutors    = $tutors;
    }

    public function search(
        $page,
        $perPage,
        $sort     = null,
        $subjects = null,
        $location = null
    ) {
    
        $sort = $sort ?: static::SORT_BEST;

        switch ($sort) {
            case TutorSearcher::SORT_RATING:
                $method = 'ByRating';
                break;

            case TutorSearcher::SORT_DISTANCE:
                $method = 'ByDistance';
                break;

            default:
                $method = 'ByBestMatch';
        }

        $items = call_user_func_array([$this->tutors, 'get'.$method], [
            $subjects,
            $location,
            $page,
            $perPage
        ]);

        $count = call_user_func_array([$this->tutors, 'count'.$method], [
            $subjects,
            $location
        ]);

        return [$items, $count];
    }

    public function countReviews($command, $subjects, $location)
    {
        if ($subjects === null && $location === null) {
            return null;
        } else {
            return $this->tutors->countReviewsBySubjectsAndLocation($subjects, $location);
        }
    }
    public function averageReview($command, $subjects, $location)
    {
        if ($subjects === null && $location === null) {
            return null;
        } else {
            return $this->tutors->averageReviewBySubjectsAndLocation($subjects, $location);
        }
    }    
}
