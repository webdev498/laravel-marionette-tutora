<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Transformers\StudentTransformer;
use Illuminate\Auth\AuthManager as Auth;

class AccountController extends StudentController
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
     * Show the messages to the user
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $student = $this->auth->user();
        $preload = [
            'user' => $this->transformItem($student, new StudentTransformer(), [
                'include' => [
                    'private',
                    'addresses',
                ],
            ]),
            'cookies' => array_values(array_filter([
                'dismissable_student_account_personal_introduction',
                'dismissable_student_account_payment_introduction',
            ], function ($name) use ($request) {
                return ! $request->cookies->has($name);
            })),
        ];

        return view('student.account.index', compact('student', 'preload'));
    }

}
