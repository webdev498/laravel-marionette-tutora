<?php

namespace App\Http\Controllers\Tutor;

use Cookie;
use App\Presenters\TutorPresenter;
use App\Transformers\TutorTransformer;
use Illuminate\Auth\AuthManager as Auth;

class AccountController extends TutorController
{

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * Create an instance of this
     *
     * @param  Auth $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Redirect the user to the first account page
     *
     * @return Response
     */
    public function index()
    {
        $tutor = $this->auth->user();

        // Preloaded data
        $preload = [
            'load' => 'tutors.account',
            'user' => $this->transformItem($tutor, new TutorTransformer(), [
                'include' => [
                    'private',
                    'profile',
                    'addresses',
                    'students',
                    'students.private',
                    'subjects',
                    'identity_document',
                    'requirements',
                ],
            ]),
            'cookies' => array_values(array_filter([
                'dismissable_tutor_account_personal_introduction',
                'dismissable_tutor_account_payment_introduction',
                'dismissable_tutor_account_identification_introduction',
                'dismissable_tutor_account_identification_verified',
            ], function ($name) {
                return ! Cookie::has($name);
            })),
        ];

        // Presented data
        $tutor = $this->presentItem($tutor, new TutorPresenter(), [
            'include' => [
                'profile',
                'requirements',
            ]
        ]);

        return view('tutor.account.index', compact('tutor', 'preload'));
    }

}
