<?php namespace App\Presenters;

use App\Geocode\Location;
use App\Pagination\SearchPaginator;
use App\Presenters\TutorPresenter;
use App\Search\Results;
use App\Search\SearchUrlGenerator;
use App\Search\TutorSearcher;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\ParamBag;

class SearchResultsPresenter extends AbstractPresenter
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'input'
    ];

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'breadcrumbs',
        'titles',
        'tutors',
        'sort_options',
        'canonical',
    ];

    /**
     * Turn this object into a generic array
     *
     * @param  Results $results
     * @return array
     */
    public function transform(Results $results)
    {

        return [
            'meta' => [
                'pagination' => $results->tutors->render(),
                'nextPage'   => $results->tutors->getNextUrl(),
                'prevPage'   => $results->tutors->getPreviousUrl(),
                'total'      => $results->tutors->total(),
            ]
        ];
    }

    /**
     * Include input
     *
     * @param  Results $results
     * @return Array
     */
    public function includeInput(Results $results, ParamBag $parameters = null)
    {
        return $this->item($parameters, function ($parameters) {
            $subject  = $parameters->subject;
            $location = $parameters->location;
            return [
                'subject'  => is_array($subject) ? head($subject) : null,
                'location' => is_array($location) ? head($location) : null,
            ];
        });
    }

    /**
     * Include titles
     *
     * @param  Results $results
     * @return Array
     */
    public function includeTitles(Results $results)
    {
        return $this->item($results, function (Results $results) {
            $titles = [
                'subject'  => null,
                'location' => null,
            ];

            if ($results->subjects instanceof Collection && ($subject = $results->subjects->first()) !== null) {
                array_set($titles, 'subject', $subject->title);
            }

            if ($results->location instanceof Location && ($location = $results->location) !== null) {
                array_set($titles, 'location', (string) $location);
            }

            return $titles;
        });
    }

    /**
     * Include sort options
     *
     * @param  Results $results
     * @return Array
     */
    public function includeSortOptions(Results $results)
    {
        $items = [
            [
                'sort'  => TutorSearcher::SORT_BEST,
                'title' => 'Best Match',
            ],
            [
                'sort'  => TutorSearcher::SORT_RATING,
                'title' => 'Highest Rated',
            ],
        ];

        if ($results->location instanceof Location) {
            $items [] = [
                'sort'  => TutorSearcher::SORT_DISTANCE,
                'title' => 'Closest',
            ];
        }
        
        return $this->collection($items, function ($item) use ($results) {
            
            if ($results->location instanceof Location && $results->subjects == null) {
                
                $url = route('search.location', [
                    'location' => $results->command->location,
                    'sort'     => $item['sort']
                ]);
                
            }

            if ($results->location instanceof Location && $results->subjects != null) {
                $url = route('search.index', [
                    'subject'  => $results->command->subject,
                    'location' => $results->command->location,
                    'sort'     => $item['sort']
                ]);
            
            } 

            if (!($results->location instanceof Location) && $results->subjects != null) {
                $url = route('search.subject', [
                    'subject'  => $results->command->subject,
                    'sort'     => $item['sort']
                ]);

            }

            if (!($results->location instanceof Location) && $results->subjects == null) {
                $url = route('search.none', [
                    'sort'     => $item['sort']
                ]);

            }

            return [
                'title' => $item['title'],
                'url'   => $url,
                'active' => ($results->command->sort ?: TutorSearcher::SORT_BEST) === $item['sort']
            ];
        });
    }

    /**
     * Include tutors
     *
     * @param  Results $results
     * @return Array
     */
    protected function includeTutors(Results $results)
    {
        $data = [];

        if ($results->subjects) {
            if ($results->subjects->first()->depth == 1)
            {
                $data['searchSubject'] = $results->subjects->first();
            } else {
                $data['searchSubject'] = $results->subjects->first()->parent()->get()->first();
            }
        }

        return $this->collection($results->tutors, new TutorPresenter([
            'include' => [
                'private',
                'profile',
                'subjects',
                'background_checks',
                'addresses',
            ], 
        ], $data));

    }

    protected function includeCanonical(Results $results)
    {
        $breadcrumbs = $this->includeBreadcrumbs($results);
        $cleanUrl = last($breadcrumbs->getData());
        $page =$results->command->page;
        $url = $cleanUrl['url'];
        $data = ['page' => $page];
        $queryString =  http_build_query($data);
        $fullUrl = $url . '?' . $queryString;
        //return $this->item($fullUrl);
        // return $url . '?' . $queryString;
    }


    /**
     * Include breadcrumbs
     *
     * @param  Results $results
     * @return Array
     */
    protected function includeBreadcrumbs(Results $results)
    {
        $breadcrumbs = [];
        $subjects = $results->subjects;
        $subject = $subjects ? $subjects->first() : null;

        $location = $results->location;

        if ($subject !== null) {
            
            $parts = explode(' / ', $subject->path);
            $parts = array_splice($parts, 1);

            $parent = '';
            $parts  = array_map(function ($part) use (&$parent) {
                $parent = $parent.' '.$part;

                return [
                    'title' => $part,
                    'full'  => $parent,
                    'url'   => route('search.subject', [
                        'subject' => str_slug($parent),
                    ]),
                ];
            }, $parts);

            $breadcrumbs = array_merge($breadcrumbs, $parts);
        }

        if ( ! $subject && $location) {
            // Find a pretty location name
            $parts = array_filter([
                $location->city ?: $location->county,
                // $location->county
            ]);

            // Or, just pop the first part of the postcode off
            if ( ! $parts) {
                $parts = explode(' ', $location->postcode);
                $parts = array_splice($parts, 0, 1);
            }

            $parent = '';
            $parts  = array_map(function ($part) use (&$parent, $breadcrumbs) {
                $parent = $parent.' '.$part;

                return [
                    'title' => $part,
                    'url'   => route('search.location', [
                        
                        'location' => str_slug($parent),
                    ]),
                ];
            }, $parts);
            
            $breadcrumbs = array_merge($breadcrumbs, $parts);
        }

        if ($location && $subject) {

            // Find a pretty location name
            $parts = array_filter([
                $location->city ?: $location->county,
                // $location->county
            ]);

            // Or, just pop the first part of the postcode off
            if ( ! $parts) {
                $parts = explode(' ', $location->postcode);
                $parts = array_splice($parts, 0, 1);
            }

            $parent = '';
            $parts  = array_map(function ($part) use (&$parent, $breadcrumbs) {
                $parent = $parent.' '.$part;

                return [
                    'title' => $part,
                    'url'   => route('search.index', [
                        'subject' => str_slug(array_get(end($breadcrumbs), 'full')),
                        'location' => str_slug($parent),
                    ]),
                ];
            }, $parts);

            $breadcrumbs = array_merge($breadcrumbs, $parts);
        }

        $breadcrumbs = array_filter($breadcrumbs);
        
        return $this->collection($breadcrumbs, function ($crumb) {
            return $crumb;
        });

    }

}
