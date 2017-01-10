<?php

namespace App\Http\Controllers;

use App\Geocode\Contracts\GeocoderInterface;
use App\Location;
use App\Presenters\JobPresenter;
use App\Repositories\Contracts\JobRepositoryInterface;

class TutoringJobsController extends Controller
{
    /**
     * Display a listing of the major cities in all regions.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $locations = config('sitelinks.locations');

        foreach ($locations as &$location) {
            $location = array_where(
                $location,
                function ($key, $value) {
                    return $value['level'] == 'top';
                }
            );
        }

        return view(
            'tutoring-jobs.index',
            compact('locations')
        );
    }

    /**
     * Display the available tutoring jobs in a certain location
     *
     * @param JobRepositoryInterface $jobs
     * @param GeocoderInterface      $geocoder
     * @param                        $city
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show(JobRepositoryInterface $jobs, GeocoderInterface $geocoder, $city)
    {
        $geocodedLocation = $geocoder->geocode($city);
        $items = $jobs->getByDateCreated(
            null,
            new Location(
                [
                    'longitude' => $geocodedLocation->longitude,
                    'latitude'  => $geocodedLocation->latitude
                ]
            ),
            [],
            null,
            15
        );

        $meta_parameters = ['location' => ucwords($city)];

        $jobs = $this->presentCollection(
            $items,
            new JobPresenter(),
            [
                'include' => [
                    'location',
                    'subject',
                    'student'
                ],
                'meta'    => [
                    'count' => $items->count(),
                ]
            ]
        );

        return view(
            'tutoring-jobs.show',
            compact(
                'jobs',
                'city',
                'meta_parameters'
            )
        );
    }
}
