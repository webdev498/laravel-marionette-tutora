<?php namespace App\Presenters;

use App\Job;
use App\Tutor;
use App\Pagination\SearchPaginator;
use App\Search\JobsResult;
use App\Search\SearchUrlGenerator;
use App\Search\JobSearcher;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\ParamBag;

class SearchTutorJobsPresenter extends AbstractPresenter
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
    ];

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'jobs',
        'sort_options',
        'filter_options',
    ];

    /**
     * Turn this object into a generic array
     *
     * @param  JobsResult $results
     * @return array
     */
    public function transform(JobsResult $results)
    {
        return [
            'meta' => [
                'pagination'   => $results->jobs->render(),
                'nextPage'     => $results->jobs->getNextUrl(),
                'prevPage'     => $results->jobs->getPreviousUrl(),
                'total'        => $results->jobs->total(),
            ],
            'activeFilter' => trans('jobs.filters')[$results->command->filter ?: JobSearcher::FILTER_SUBJECTS],
        ];
    }

    /**
     * Include sort options
     *
     * @param  JobsResult $results
     * @return Array
     */
    public function includeSortOptions(JobsResult $results)
    {
        $items = [
            [
                'sort'  => JobSearcher::SORT_CLOSEST,
                'title' => 'Closest',
            ],
            [
                'sort'  => JobSearcher::SORT_DATE_CREATED,
                'title' => 'Date Created',
            ],
        ];

        return $this->collection($items, function ($item) use ($results) {

            $url = route('tutor.jobs.index', [
                'sort'   => $item['sort'],
                'filter' => $results->command->filter ?: null,
            ]);

            return [
                'title' => $item['title'],
                'url'   => $url,
                'active' => ($results->command->sort ?: JobSearcher::SORT_CLOSEST) === $item['sort']
            ];
        });
    }

    /**
     * Include filter options
     *
     * @param  JobsResult $results
     * @return Array
     */
    public function includeFilterOptions(JobsResult $results)
    {
        $items = [
            [
                'filter' => JobSearcher::FILTER_SUBJECTS,
                'title'  => trans('jobs.filters')[JobSearcher::FILTER_SUBJECTS],
            ],
            [
                'filter' => JobSearcher::FILTER_NONE,
                'title'  => trans('jobs.filters')[JobSearcher::FILTER_NONE],
            ],
            [
                'filter' => JobSearcher::FILTER_FAVOURITES,
                'title'  => trans('jobs.filters')[JobSearcher::FILTER_FAVOURITES],
            ],
        ];

        return $this->collection($items, function ($item) use ($results) {

            $url = route('tutor.jobs.index', [
                'filter' => $item['filter'],
                'sort'   => $results->command->sort ?: null,
            ]);

            $isActive = ($results->command->filter ?: JobSearcher::SORT_CLOSEST) === $item['filter'];

            return [
                'title'  => $item['title'],
                'url'    => $url,
                'active' => $isActive,
            ];
        });
    }

    /**
     * Include jobs
     *
     * @param  JobsResult $results
     * @return Array
     */
    protected function includeJobs(JobsResult $results)
    {
        /** @var Tutor $tutor */
        $tutor = $results->command->tutor;

        return $this->collection($results->jobs, new JobPresenter([
            'include' => [
                'subject',
                'location',
                'student',
                'tutor'
            ],
            'tutor' => $tutor,
        ]));
    }
}
