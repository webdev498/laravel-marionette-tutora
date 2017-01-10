<?php namespace App\Http\Controllers\Tutor;

use App\Dialogue\UserDialogue;
use App\Dialogue\UserDialogueInteraction;
use App\User;
use App\Tutor;
use App\UserProfile;
use App\UserRequirement;
use Illuminate\Http\Request;
use App\Presenters\TutorPresenter;
use App\Http\Controllers\Controller;
use App\Transformers\TutorTransformer;
use Illuminate\Auth\AuthManager as Auth;
use App\Repositories\Contracts\TutorRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Profiler\Profile;

class ProfileController extends Controller
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
     * Create a new filter instance.
     *
     * @param Auth                                             $auth
     * @param EloquentTutorRepository|TutorRepositoryInterface $tutors
     *
     */
    public function __construct(
        Auth $auth,
        TutorRepositoryInterface $tutors
    ) {
        $this->auth   = $auth;
        $this->tutors = $tutors;
        $this->middleware('tutor.requirements');
    }

    public function edit(Request $request, $uuid, $section = null)
    {
        // Load the tutor
        $user = $this->auth->user();

        if ($user instanceof Tutor) {
            if ($section != "welcome"
                && !UserDialogueInteraction::existsForUser(
                    $user,
                    "welcome"
                )
            ) {
                return UserDialogue::Show(
                    "welcome",
                    [$user->uuid]
                );
            }

            if ($section != "welcome"
                && $section != "quiz_intro"
                && $section != "quiz_prep"
                && $section != "quiz_questions"
                && $user->requirements->onlyPending(UserRequirement::QUIZ_QUESTIONS)
            ) {
                return UserDialogue::Show(
                    "quiz_intro",
                    [$user->uuid]
                );
            }

            if ($section != "welcome"
                && $section != "quiz_intro"
                && $section != "quiz_prep"
                && $section != "quiz_questions"
                && $section != "review_notification"
                && $this->tutorHasEnteredReview($user)
                && !UserDialogueInteraction::existsForUser(
                    $user,
                    "review_notification"
                )
            ) {
                return UserDialogue::Show(
                    "review_notification",
                    [$user->uuid],
                    route(
                        "tutor.profile.show",
                        ["uuid" => $user->uuid],
                        false
                    )
                );
            }
        }

        $tutor = $user->isAdmin()
            ? $this->tutors->findByUuid($uuid)
            : $user;

        // Force the distance to show for editing.
        $tutor->distance = '-';
        // Preloaded data
        $preload = [
            'load' => 'tutors.edit',
            'user' => $this->transformItem(
                $tutor,
                new TutorTransformer(),
                [
                    'include' => [
                        'private',
                        'addresses',
                        'students',
                        'students.private',
                        'profile',
                        'subjects',
                        'qualifications',
                        'qts',
                        'background_checks',
                        'requirements'
                    ],
                ]
            ),
        ];
        // Presented data
        $tutor = $this->presentItem(
            $tutor,
            new TutorPresenter(),
            [
                'include' => [
                    'profile',
                    'requirements',
                    'subjects',
                    'qualifications',
                    'background_checks',
                    'reviews',
                    'addresses'
                ]
            ]
        );
        // Meta Parameters data
        $metaParameters = [
            'name'      => $tutor->name,
            'short_bio' => e($tutor->profile->short_bio),
        ];

        // Return
        return view(
            'tutor.profile.edit',
            [
                'is_editable'     => true,
                'tutor'           => $tutor,
                'preload'         => $preload,
                'meta_parameters' => $metaParameters,
                'ab'              => null,
                /*
                 * @TODO Solve the issue with RequireJS and the Ziggeo SDK or find a solution to load it by the app
                 * */
                'js'              => [
                    //'//assets-cdn.ziggeo.com/v1-stable/ziggeo.js'
                ],
                'css'             => [
                    //'//assets-cdn.ziggeo.com/v1-stable/ziggeo.css'
                ]
            ]
        );
    }

    /**
     * Is the tutor's profile under review?
     *
     * @param Tutor in question
     *
     * @return boolean
     */
    private function tutorHasEnteredReview($tutor)
    {
        $profile = $tutor->profile;

        return $profile->status == UserProfile::PENDING && $profile->admin_status == UserProfile::REVIEW;
    }
}
