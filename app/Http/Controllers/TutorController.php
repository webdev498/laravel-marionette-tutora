<?php namespace App\Http\Controllers;

use App\Transformers\TutorTransformer;
use App\User;
use App\Tutor;
use App\UserProfile;
use Illuminate\Http\Request;
use App\Presenters\TutorPresenter;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard as Auth;
use App\Repositories\Contracts\TutorRepositoryInterface;
use App\Repositories\Contracts\AddressRepositoryInterface;

class TutorController extends Controller
{

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * @var TutorRepositoryInstance
     */
    protected $tutors;

    /**
     * @var AddressRepositoryInterface
     */
    protected $addresses;

    /**
     * Create a new filter instance.
     *
     * @param Auth                       $auth
     * @param EloquentTutorRepository    $tutors
     * @param AddressRepositoryInterface $addresses
     *
     * @return void
     */
    public function __construct(
        Auth $auth,
        TutorRepositoryInterface $tutors,
        AddressRepositoryInterface $addresses
    ) {
        $this->auth      = $auth;
        $this->tutors    = $tutors;
        $this->addresses = $addresses;
    }

    /**
     * Display the "Become a tutor" page
     *
     * @return Response
     */
    public function index()
    {
        return view('tutor.create');
    }

    /**
     * Display the "Become a tutor" page
     *
     * @return Response
     */
    public function create()
    {
        return view('tutor.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  string $uuid
     *
     * @return Response
     */
    public function show(Request $request, $uuid)
    {
        $tutor = $this->tutors->findByUuid($uuid);

        if ($tutor === null || ($tutor && ($tutor->deleted_at || $tutor->blocked_at))) {
            return redirect()->route(
                'home',
                [],
                301
            );
        }

        if ($tutor->profile->status !== UserProfile::LIVE || $tutor->profile->admin_status !== UserProfile::OK) {
            return redirect()->route(
                'home',
                [],
                301
            );
        }

        if (($location = session('location')) !== null) {
            $address = $this->addresses->findByTutorAndLocation(
                $tutor,
                $location
            );

            if (isset($address->distance)) {
                $tutor->distance = $address->distance;
            }
        }

        $tutor = $this->presentItem(
            $tutor,
            new TutorPresenter(),
            [
                'include' => [
                    'private',
                    'profile',
                    'subjects',
                    'badges',
                    'qualifications',
                    'background_checks',
                    'reviews',
                    'addresses'
                ]
            ]
        );

        $meta_parameters = [
            'name'      => "{$tutor->first_name} {$tutor->last_name}",
            'title'     => $this->formatTitle($tutor),
            'short_bio' => e($tutor->profile->short_bio),
            'noindex'   => $tutor->profile->status == UserProfile::LIVE ? false : true,
        ];

        $preload = [
            'tutorMapSettings' => [
                'center' => [
                    'lat' => $tutor->addresses->default->latitude,
                    'lng' => $tutor->addresses->default->longitude,
                ],
            ],
            'user'             => $this->transformItem(
                $this->tutors->findByUuid($uuid),
                new TutorTransformer(),
                [
                    'include' => [
                        'profile'
                    ]
                ]
            )
        ];

        $js  = [
            //'//assets-cdn.ziggeo.com/v1-stable/ziggeo.js'
        ];
        $css = [
            //'//assets-cdn.ziggeo.com/v1-stable/ziggeo.css'
        ];

        return view(
            'tutor',
            compact(
                'tutor',
                'meta_parameters',
                'preload',
                'js',
                'css'
            )
        );
    }

    protected function formatTitle($tutor)
    {
        return "$tutor->name | Tutor in " . $tutor->addresses->default->city . ' | Tutora';
    }

}
