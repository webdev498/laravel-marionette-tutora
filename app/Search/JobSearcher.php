<?php namespace App\Search;

use App\Tutor;
use App\Address;
use App\Location;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Repositories\Contracts\JobRepositoryInterface;
use App\Repositories\Contracts\LocationRepositoryInterface;

class JobSearcher extends AbstractSearcher
{

    const SORT_CLOSEST      = 'closest';
    const SORT_DATE_CREATED = 'date_created';

    const FILTER_SUBJECTS   = 'subjects';
    const FILTER_FAVOURITES = 'favourites';
    const FILTER_NONE       = 'none';

    /**
     * @var LocationRepositoryInterface
     */
    protected $locations;

    /**
     * @var JobRepositoryInterface
     */
    protected $jobs;

    /**
     * @param LocationRepositoryInterface $locations
     * @param JobRepositoryInterface $jobs
     */
    public function __construct(
        LocationRepositoryInterface $locations,
        JobRepositoryInterface      $jobs
    ) {
        $this->locations = $locations;
        $this->jobs     = $jobs;
    }

    /**
     * @param Tutor|null    $tutor
     * @param Carbon|null   $afterDate
     * @param null          $sort
     * @param null          $filter
     * @param boolean       $notApplied
     *
     * @return Collection
     */
    public function searchForTutor(
        Tutor $tutor      = null,
        Carbon $afterDate = null,
        $sort             = null,
        $filter           = null,
        $notApplied       = false
    ) {
        $address  = $tutor->addresses()->where('name', Address::NORMAL)->first();
        $location = Location::makeFromAddress($address);

        $notAppliedById = null;
        if($notApplied) {
            $notAppliedById = $tutor->id;
        }

        $result = $this->search(null, null, $sort, $filter, $tutor, $location, $afterDate, $notAppliedById);

        return $result;
    }

    /**
     * @param null          $page
     * @param null          $perPage
     * @param null          $sort
     * @param null          $filter
     * @param Tutor         $tutor
     * @param null          $location
     * @param Carbon|null   $afterDate
     * @param integer|null  $notAppliedById
     *
     * @return array
     */
    public function search(
        $page               = null,
        $perPage            = null,
        $sort               = null,
        $filter             = null,
        Tutor $tutor        = null,
        $location           = null,
        Carbon $afterDate   = null,
        $notAppliedById     = null
    ) {

        $subjects     = null;
        $favouritedBy = null;

        $method = $this->getSortMethodName($sort);

        $filter = $filter ?: static::FILTER_SUBJECTS;
        switch ($filter) {
            case JobSearcher::FILTER_SUBJECTS:
                $subjects = $tutor->subjects;
                $parents  = $subjects->lists('parent')->unique();
                $subjects = $subjects->merge($parents->all());
                break;
            case JobSearcher::FILTER_FAVOURITES:
                $favouritedBy = [$tutor->id];
                break;
            case JobSearcher::FILTER_NONE:
            default:
                break;
        }

        $items = call_user_func_array([$this->jobs, 'get'.$method], [
            $subjects,
            $location,
            $favouritedBy,
            $page,
            $perPage,
            $afterDate,
            $notAppliedById
        ]);

        $count = call_user_func_array([$this->jobs, 'count'.$method], [
            $subjects,
            $location,
            $favouritedBy,
            $afterDate,
            $notAppliedById
        ]);

        return [$items, $count];
    }

    /**
     * @param $sort
     *
     * @return string
     */
    protected function getSortMethodName($sort)
    {
        $sort = $sort ?: static::SORT_CLOSEST;
        switch ($sort) {
            case JobSearcher::SORT_CLOSEST:
                $method = 'ByDistance';
                break;

            case JobSearcher::SORT_DATE_CREATED:
                $method = 'ByDateCreated';
                break;

            default:
                $method = 'ByDistance';
        }

        return $method;
    }
}
