<?php

use Illuminate\Http\Request;
use App\Student;
use App\Tutor;
use Illuminate\Foundation\Bus\DispatchesCommands;

if (environment('testing')) {
    $router->get(
        '/testing/migrate',
        function () {
            Artisan::call('migrate');
            Artisan::call('db:seed');
        }
    );
}

$router->get(
    '/404',
    [
        'as'   => '404',
        'uses' => function () {
            return abort(404);
        }
    ]
);

$router->get(
    '/test',
    'homeController@test'
);

/**
 * Profile pictures
 *
 * @todo This takes up a lot of time! Optimize it.
 */
$router->get(
    '/img/profile-pictures/{name}@{width}x{height}.{ext}',
    [
        'as'   => 'profile-picture.show',
        'uses' => 'ProfilePictureController@show',
    ]
);

/**
 * Images
 */
$router->get(
    '/images/{filter}/{path}/{filename}.{ext}',
    [
        'as'   => 'files.images.show',
        'uses' => 'Files\ImageController@show',
    ]
)
    ->where(
        'path',
        '(.*)'
    );

$router->get(
    '/images/{uuid}',
    [
        'as'   => 'files.images.show.uuid',
        'uses' => 'Files\ImageController@showByUuid',
    ]
);

/**
 * ID
 */
$router->get(
    '/img/identity-document/{uuid}.{ext}',
    [
        'as'   => 'identity-document.show',
        'uses' => function ($uuid, $ext) {
            $user = Auth::user();

            if ($user
                && (($user->identityDocument && $user->identityDocument->uuid === $uuid) || $user instanceof \App\Admin)
            ) {
                $path = storage_path() . '/app/identity-documents';

                return \Image::make("{$path}/{$uuid}.{$ext}")
                    ->response();
            }

            return abort(404);
        }
    ]
);

/**
 * Password Resets
 */
$router->get(
    '/lost-password',
    [
        'as'   => 'password.index',
        'uses' => 'PasswordController@index',
    ]
);

$router->post(
    '/lost-password',
    [
        'as'   => 'password.create',
        'uses' => 'PasswordController@create',
    ]
);

$router->get(
    '/reset-password/{token}',
    [
        'as'   => 'password.edit',
        'uses' => 'PasswordController@edit',
    ]
);

$router->post(
    '/reset-password',
    [
        'as'   => 'password.update',
        'uses' => 'PasswordController@update',
    ]
);

/**
 * General
 */

$router->get(
    '/',
    [
        'as'   => 'home',
        'uses' => 'HomeController@index',
    ]
);

$router->get(
    '/about-us',
    [
        'as'   => 'about.index',
        'uses' => 'AboutController@index',
    ]
);

$router->post(
    '/about-us',
    [
        'as'   => 'about.contact',
        'uses' => 'AboutController@contact',
    ]
);

$router->get(
    '/faqs',
    [
        'as'   => 'faqs.student',
        'uses' => 'FaqsController@student',
    ]
);

$router->get(
    '/faqs/for-tutors',
    [
        'as'   => 'faqs.tutor',
        'uses' => 'FaqsController@tutor',
    ]
);

$router->get(
    '/terms',
    [
        'as'   => 'terms.index',
        'uses' => 'TermsController@index',
    ]
);

$router->get(
    '/privacy-policy',
    [
        'as'   => 'policy.privacy',
        'uses' => 'PolicyController@privacy',
    ]
);

/**
 * Blog
 */
$router->get(
    '/articles',
    [
        'as'   => 'articles.index',
        'uses' => 'Resources\ArticleController@index',
    ]
);

$router->get(
    '/articles/{slug}',
    [
        'as'   => 'articles.show',
        'uses' => 'Resources\ArticleController@show',
    ]
);
/**
 * Sitelinks
 */

$router->get(
    '/locations',
    [
        'as'   => 'locations.index',
        'uses' => 'LocationsController@index',
    ]
);

$router->get(
    '/locations/{region}',
    [
        'as'   => 'locations.show',
        'uses' => 'LocationsController@show'
    ]
);

$router->get(
    '/subjects',
    [
        'as'   => 'subjects.index',
        'uses' => 'SubjectsController@index'
    ]
);

$router->get(
    '/tutoring-jobs',
    [
        'as'   => 'tutoring.jobs.index',
        'uses' => 'TutoringJobsController@index'
    ]
);

$router->get(
    '/tutoring-jobs/{city}',
    [
        'as'   => 'tutoring.jobs.show',
        'uses' => 'TutoringJobsController@show'
    ]
);

/**
 * Search
 */
$router->post(
    '/search',
    [
        'as'   => 'search.create',
        'uses' => 'SearchController@create'
    ]
);

$router->get(
    '/search/{subject}/tutors-near/{location}',
    function ($subject, $location) {
        Log::info(
            "[ Redirect ] /search/{$subject}/tutors-near/{$location} -> /search/{$subject}/tutors-in/{$location}"
        );

        return redirect(
            "/search/{$subject}/tutors-in/{$location}",
            301
        );
    }
);

$router->get(
    '/search/{subject}/tutors-in/{location}',
    [
        'as'   => 'search.index',
        'uses' => 'SearchController@index'
    ]
);

$router->get(
    '/search/{subject}/tutors',
    [
        'as'   => 'search.subject',
        'uses' => 'SearchController@index'
    ]
);

$router->get(
    '/search/tutors-in/{location}',
    [
        'as'   => 'search.location',
        'uses' => 'SearchController@location'
    ]
);

$router->get(
    '/search',
    [
        'as'   => 'search.none',
        'uses' => 'SearchController@none'
    ]
);

/**
 * View/Edit a profile
 */
$router->get(
    '/tutors/{uuid}/{section?}/{tab?}',
    [
        'as'         => 'tutor.profile.show',
        'middleware' => 'tutor.requirements',
        'uses'       => function (
            Request $request,
            $uuid = null,
            $section = null,
            $tab = null
        ) {
            // Forward on show vs editing to the appropriate controller
            $user = $request->user();

            $action = $user && ($user->isAdmin() || $user->uuid === $uuid)
                ? 'App\Http\Controllers\Tutor\ProfileController@edit'
                : 'App\Http\Controllers\TutorController@show';

            // dd($action);
            list($class, $method) = explode(
                '@',
                $action
            );

            return app($class)->$method(
                $request,
                $uuid,
                $section,
                $tab
            );
        }
    ]
);

// Show the dialogue for sending a message (typically from the tutors profile).
$router->get(
    '/message/{uuid}',
    [
        'as'   => 'message.create',
        'uses' => 'TutorController@show'
    ]
);

/**
 * Guest
 */
$router->group(
    [
        'middleware' => 'guest'
    ],
    function ($router) {
        $router->get(
            '/login',
            [
                'as'   => 'auth.login',
                'uses' => 'HomeController@index',
            ]
        );

        // Student
        $router->get(
            '/sign-up',
            [
                'as'   => 'register.student',
                'uses' => 'HomeController@index',
            ]
        );

        // Tutor
        $router->get(
            '/become-a-tutor',
            [
                'as'   => 'tutor.index',
                'uses' => 'TutorController@index'
            ]
        );

        $router->get(
            '/become-a-tutor/sign-up',
            [
                'as'   => 'register.tutor',
                'uses' => 'TutorController@index'
            ]
        );
    }
);

/**
 * Auth
 */
$router->get(
    '/logout',
    [
        'as'   => 'auth.logout',
        'uses' => 'AuthController@logout',
    ]
);

$router->get(
    '/sign-up/confirm',
    [
        'as'   => 'register.confirm',
        'uses' => 'AuthController@confirm',
    ]
);

$router->get(
    '/message',
    [
        'as'   => 'message.redirect',
        'uses' => 'MessageController@redirect'
    ]
);

/**
 * Unsubscribe
 */
$router->get(
    '/unsubscribe',
    [
        'as'   => 'unsubscribe',
        'uses' => 'SubscriptionsController@unsubscribeFromUrl',
    ]
);

/*
 * Tutor links
 */

$router->group(
    [
        'middleware' => [
            'auth.tutor',
            'tutor.requirements',
            'tutor.messages'
        ],
    ],
    function ($router) {

        /**
         * Tutor dasboard, messages, etc..
         */
        $router->group(
            [
                'prefix' => '/tutor',
            ],
            function ($router) {
                $router->get(
                    '/dashboard/{dialogue?}',
                    [
                        'as'   => 'tutor.dashboard.index',
                        'uses' => 'Tutor\DashboardController@index',
                    ]
                );

                $router->get(
                    '/messages',
                    [
                        'as'   => 'tutor.messages.index',
                        'uses' => 'Tutor\MessagesController@index',
                    ]
                );

                $router->get(
                    '/messages/{id}/{dialogue?}',
                    [
                        'as'   => 'tutor.messages.show',
                        'uses' => 'Tutor\MessagesController@show',
                    ]
                );

                $router->post(
                    '/messages/{id}',
                    [
                        'as'   => 'tutor.messages.store',
                        'uses' => 'Tutor\MessagesController@store',
                    ]
                );

                $router->get(
                    '/students',
                    [
                        'as'   => 'tutor.students.index',
                        'uses' => 'Tutor\StudentsController@index',
                    ]
                );

                $router->get(
                    '/lessons',
                    [
                        'as'   => 'tutor.lessons.index',
                        'uses' => 'Tutor\LessonsController@index',
                    ]
                );

                $router->get(
                    '/lessons/create',
                    [
                        'as'   => 'tutor.lessons.create',
                        'uses' => 'Tutor\LessonsController@index'
                    ]
                );

                $router->get(
                    '/lessons/{booking}/edit',
                    [
                        'as'   => 'tutor.lessons.edit',
                        'uses' => 'Tutor\LessonsController@index'
                    ]
                );

                $router->get(
                    '/lessons/{booking}/cancel',
                    [
                        'as'   => 'tutor.lessons.cancel',
                        'uses' => 'Tutor\LessonsController@index'
                    ]
                );

                // TUTOR JOBS

                $router->get(
                    '/jobs',
                    [
                        'as'   => 'tutor.jobs.index',
                        'uses' => 'Tutor\JobsController@index',
                    ]
                );

                $router->get(
                    '/jobs/{uuid}',
                    [
                        'as'   => 'tutor.jobs.show',
                        'uses' => 'Tutor\JobsController@show',
                    ]
                );

                $router->get(
                    '/account',
                    [
                        'as'   => 'tutor.account.index',
                        'uses' => function () {
                            return redirect()->route(
                                'tutor.account.personal.index'
                            );
                        }
                    ]
                );

                $router->get(
                    '/account/personal-details',
                    [
                        'as'   => 'tutor.account.personal.index',
                        'uses' => 'Tutor\AccountController@index',
                    ]
                );

                $router->get(
                    '/account/payment-details',
                    [
                        'as'   => 'tutor.account.payment.index',
                        'uses' => 'Tutor\AccountController@index',
                    ]
                );

                $router->get(
                    '/account/identification',
                    [
                        'as'   => 'tutor.account.identification.index',
                        'uses' => 'Tutor\AccountController@index',
                    ]
                );
            }
        );
    }
);

/*
 * Student links
 */
$router->group(
    [
        'middleware' => 'auth.student',
    ],
    function ($router) {

        $router->get(
            '/review/{tutor}',
            [
                'as'   => 'review.create',
                'uses' => 'Student\TutorsController@index',
            ]
        );

        $router->get(
            '/review/{tutor}/student/{student}',
            [
                'as'   => 'review.create_for_student',
                'uses' => 'Student\TutorsController@index',
            ]
        );

        /**
         * Student dasboard, messages, etc..
         */
        $router->group(
            [
                'prefix' => 'student',
            ],
            function ($router) {
                $router->get(
                    '/dashboard/{dialogue?}',
                    [
                        'as'   => 'student.dashboard.index',
                        'uses' => 'Student\DashboardController@index',
                    ]
                );

                $router->get(
                    '/messages',
                    [
                        'as'   => 'student.messages.index',
                        'uses' => 'Student\MessagesController@index',
                    ]
                );

                $router->get(
                    '/request-tutor',
                    [
                        'as'   => 'student.request-tutor',
                        'uses' => 'Student\DashboardController@index',
                    ]
                );

                $router->get(
                    '/messages/{id}/{dialogue?}',
                    [
                        'as'   => 'student.messages.show',
                        'uses' => 'Student\MessagesController@show',
                    ]
                );

                $router->post(
                    '/messages/{id}',
                    [
                        'as'   => 'student.messages.store',
                        'uses' => 'Student\MessagesController@store',
                    ]
                );

                $router->get(
                    '/tutors',
                    [
                        'as'   => 'student.tutors.index',
                        'uses' => 'Student\TutorsController@index',
                    ]
                );

                $router->get(
                    '/lessons',
                    [
                        'as'   => 'student.lessons.index',
                        'uses' => 'Student\LessonsController@index',
                    ]
                );

                $router->get(
                    '/lessons/{lesson}',
                    [
                        'as'   => 'student.lessons.show',
                        'uses' => 'Student\LessonsController@show',
                    ]
                );

                $router->get(
                    '/lessons/{booking}/confirm',
                    [
                        'as'   => 'student.lessons.confirm',
                        'uses' => 'Student\LessonsController@confirm'
                    ]
                );

                $router->get(
                    '/lessons/{booking}/confirmed',
                    [
                        'as'   => 'student.lessons.confirmed',
                        'uses' => 'Student\LessonsController@confirmed'
                    ]
                );

                $router->get(
                    '/lessons/{booking}/pay',
                    [
                        'as'   => 'student.lessons.pay',
                        'uses' => 'Student\LessonsController@index'
                    ]
                );

                $router->get(
                    '/lessons/{booking}/cancel',
                    [
                        'as'   => 'student.lessons.cancel',
                        'uses' => 'Student\LessonsController@index'
                    ]
                );

                $router->get(
                    '/account',
                    [
                        'as'   => 'student.account.index',
                        'uses' => function () {
                            return redirect()->route(
                                'student.account.personal.index'
                            );
                        }
                    ]
                );

                $router->get(
                    '/account/personal-details',
                    [
                        'as'   => 'student.account.personal.index',
                        'uses' => 'Student\AccountController@index',
                    ]
                );

                $router->get(
                    '/account/payment-details',
                    [
                        'as'   => 'student.account.payment.index',
                        'uses' => 'Student\AccountController@index',
                    ]
                );
            }
        );
    }
);

/*
 * Admin links
 */
$router->group(
    [
        'middleware' => 'auth.admin',
    ],
    function ($router) {

        /**
         * Admin dasboard, messages, etc..
         */
        $router->group(
            [
                'prefix' => 'admin',
            ],
            function ($router) {
                $router->get(
                    '/',
                    function () {
                        return redirect()
                            ->route('admin.relationships.index');
                    }
                );

                $router->get(
                    '/dashboard',
                    [
                        'as'   => 'admin.dashboard.index',
                        'uses' => 'Admin\DashboardController@index',
                    ]
                );

                $router->get(
                    '/relationships',
                    [
                        'as'   => 'admin.relationships.index',
                        'uses' => 'Admin\RelationshipsController@index',
                    ]
                );

                $router->get(
                    '/relationships/create',
                    [
                        'as'   => 'admin.relationships.create',
                        'uses' => 'Admin\RelationshipsController@create',
                    ]
                );

                $router->post(
                    '/relationships',
                    [
                        'as'   => 'admin.relationships.store',
                        'uses' => 'Admin\RelationshipsController@store',
                    ]
                );

                $router->get(
                    '/relationships/{id}',
                    [
                        'as'   => 'admin.relationships.show',
                        'uses' => 'Admin\RelationshipsController@show',
                    ]
                );

                $router->get(
                    '/relationships/{id}/details',
                    [
                        'as'   => 'admin.relationships.details.show',
                        'uses' => 'Admin\Relationships\DetailsController@show',
                    ]
                );

                $router->get(
                    '/relationships/{id}/details/edit',
                    [
                        'as'   => 'admin.relationships.details.edit',
                        'uses' => 'Admin\Relationships\DetailsController@edit',
                    ]
                );

                $router->post(
                    '/relationships/{id}/details',
                    [
                        'as'   => 'admin.relationships.details.update',
                        'uses' => 'Admin\Relationships\DetailsController@update',
                    ]
                );

                $router->post(
                    '/relationships/{id}/tasks',
                    [
                        'as'   => 'admin.relationships.tasks.store',
                        'uses' => 'Admin\Relationships\TasksController@store',
                    ]
                );

                $router->post(
                    '/relationships/{id}/tasks/{task}',
                    [
                        'as'   => 'admin.relationships.tasks.update',
                        'uses' => 'Admin\Relationships\TasksController@update',
                    ]
                );

                $router->delete(
                    '/relationships/{relationship}/tasks/{task}',
                    [
                        'as'   => 'admin.relationships.tasks.destroy',
                        'uses' => 'Admin\Relationships\TasksController@destroy',
                    ]
                );

                $router->put(
                    '/relationships/{id}/notes',
                    [
                        'as'   => 'admin.relationships.notes.update',
                        'uses' => function (Request $request, $relationship) {
                            // Relationship
                            $relationship = \App\Relationship::findOrFail(
                                $relationship
                            );
                            // Notes
                            $note = $relationship->note ?: new \App\Note();
                            // Attributes
                            $note->body = $request->body;
                            // Save
                            $relationship->note()->save($note);

                            // Return
                            return redirect()
                                ->route(
                                    'admin.relationships.details.show',
                                    [
                                        'id' => $relationship->id,
                                    ]
                                );
                        }
                    ]
                );

                $router->get(
                    '/relationships/{id}/messages',
                    [
                        'as'   => 'admin.relationships.messages.show',
                        'uses' => 'Admin\Relationships\MessagesController@show',
                    ]
                );

                $router->post(
                    '/relationships/{id}/messages',
                    [
                        'as'   => 'admin.relationships.messages.store',
                        'uses' => 'Admin\Relationships\MessagesController@store',
                    ]
                );

                $router->get(
                    '/relationships/{id}/lessons',
                    [
                        'as'   => 'admin.relationships.lessons.index',
                        'uses' => 'Admin\Relationships\LessonsController@index',
                    ]
                );

                $router->get(
                    '/tutors',
                    [
                        'as'   => 'admin.tutors.index',
                        'uses' => 'Admin\TutorsController@index',
                    ]
                );

                $router->get(
                    '/tutors/{uuid}',
                    [
                        'as'   => 'admin.tutors.show',
                        'uses' => 'Admin\TutorsController@show',
                    ]
                );

                $router->get(
                    '/tutors/{uuid}/delete',
                    [
                        'as'   => 'admin.tutors.delete',
                        'uses' => 'Admin\TutorsController@delete',
                    ]
                );

                $router->get(
                    '/tutors/{uuid}/block',
                    [
                        'as'   => 'admin.tutors.block',
                        'uses' => 'Admin\TutorsController@block',
                    ]
                );

                $router->get(
                    '/tutors/{uuid}/unblock',
                    [
                        'as'   => 'admin.tutors.unblock',
                        'uses' => 'Admin\TutorsController@unblock',
                    ]
                );

                $router->get(
                    '/tutors/{uuid}/personal',
                    [
                        'as'   => 'admin.tutors.personal.show',
                        'uses' => 'Admin\Tutors\PersonalController@show',
                    ]
                );

                $router->get(
                    '/tutors/{uuid}/personal/edit',
                    [
                        'as'   => 'admin.tutors.personal.edit',
                        'uses' => 'Admin\Tutors\PersonalController@edit',
                    ]
                );

                $router->post(
                    '/tutors/{uuid}/personal',
                    [
                        'as'   => 'admin.tutors.personal.update',
                        'uses' => 'Admin\Tutors\PersonalController@update',
                    ]
                );

                $router->post(
                    '/tutors/{uuid}/tasks',
                    [
                        'as'   => 'admin.tutors.tasks.store',
                        'uses' => function (Request $request, $tutor) {
                            // Tutor
                            $tutor = \App\Tutor::where(
                                'uuid',
                                '=',
                                $tutor
                            )->firstOrFail();
                            // Task
                            $task = new \App\Task();
                            // Attributes
                            $task->body      = $request->body;
                            $task->category  = $request->category;
                            $task->action_at = strtodate($request->action_at);
                            $task->action_at = strtodate(
                                $request->action_at ?: '+ 2 days'
                            );
                            // Save
                            $tutor->tasks()->save($task);

                            // Return
                            return redirect()
                                ->route(
                                    'admin.tutors.personal.show',
                                    [
                                        'uuid' => $tutor->uuid
                                    ]
                                );
                        }
                    ]
                );

                $router->post(
                    '/tutors/{uuid}/tasks/{task}',
                    [
                        'as'   => 'admin.tutors.tasks.update',
                        'uses' => function (Request $request, $tutor, $task) {
                            // Tutor
                            $tutor = \App\Tutor::where(
                                'uuid',
                                '=',
                                $tutor
                            )->firstOrFail();
                            // Task
                            $task = $tutor->tasks()->where(
                                'tasks.id',
                                '=',
                                $task
                            )->firstOrFail();
                            // Attributes
                            $task->body      = $request->body;
                            $task->category  = $request->category;
                            $task->action_at = strtodate(
                                $request->action_at ?: '+ 2 days'
                            );
                            // Save
                            $task->save();

                            // Return
                            return redirect()
                                ->route(
                                    'admin.tutors.personal.show',
                                    [
                                        'uuid' => $tutor->uuid
                                    ]
                                );
                        }
                    ]
                );

                $router->delete(
                    '/tutors/{uuid}/tasks/{task}',
                    [
                        'as'   => 'admin.tutors.tasks.destroy',
                        'uses' => function (Request $request, $tutor, $task) {
                            // Tutor
                            $tutor = \App\Tutor::where(
                                'uuid',
                                '=',
                                $tutor
                            )->firstOrFail();
                            // Task
                            $task = $tutor->tasks()->where(
                                'tasks.id',
                                '=',
                                $task
                            )->firstOrFail();
                            // Delete
                            $task->delete();

                            // Return
                            return redirect()
                                ->route(
                                    'admin.tutors.personal.show',
                                    [
                                        'uuid' => $tutor->uuid
                                    ]
                                );
                        }
                    ]
                );

                $router->put(
                    '/tutors/{uuid}/notes',
                    [
                        'as'   => 'admin.tutors.notes.update',
                        'uses' => function (Request $request, $tutor) {
                            // Tutor
                            $tutor = \App\Tutor::where(
                                'uuid',
                                '=',
                                $tutor
                            )->firstOrFail();
                            // Notes
                            $note = $tutor->note ?: new \App\Note();
                            // Attributes
                            $note->body = $request->body;
                            // Save
                            $tutor->note()->save($note);

                            // Return
                            return redirect()
                                ->route(
                                    'admin.tutors.personal.show',
                                    [
                                        'uuid' => $tutor->uuid,
                                    ]
                                );
                        }
                    ]
                );

                $router->get(
                    '/tutors/{uuid}/relationships',
                    [
                        'as'   => 'admin.tutors.relationships.index',
                        'uses' => 'Admin\Tutors\RelationshipsController@index',
                    ]
                );

                $router->get(
                    '/tutors/{uuid}/billing',
                    [
                        'as'   => 'admin.tutors.billing.index',
                        'uses' => 'Admin\Tutors\BillingController@index',
                    ]
                );

                $router->get(
                    '/tutors/{uuid}/background-check',
                    [
                        'as'   => 'admin.tutors.background_check.index',
                        'uses' => 'Admin\Tutors\BackgroundCheckController@index',
                    ]
                );

                $router->get(
                    '/tutors/{uuid}/lessons',
                    [
                        'as'   => 'admin.tutors.lessons.index',
                        'uses' => 'Admin\Tutors\LessonsController@index',
                    ]
                );

                $router->get(
                    '/tutors/{uuid}/reviews',
                    [
                        'as'   => 'admin.tutors.reviews.index',
                        'uses' => 'Admin\Tutors\ReviewsController@index',
                    ]
                );

                $router->get(
                    '/review/{uuid}/edit',
                    [
                        'as'   => 'admin.review.edit',
                        'uses' => 'Admin\Tutors\ReviewsController@index',
                    ]
                );

                $router->get(
                    '/review/{uuid}/cancel',
                    [
                        'as'   => 'admin.review.cancel',
                        'uses' => 'Admin\Tutors\ReviewsController@index',
                    ]
                );

                $router->get(
                    '/tutors/{uuid}/lessons/create',
                    [
                        'as'   => 'admin.tutors.lessons.create',
                        'uses' => 'Admin\Tutors\LessonsController@index',
                    ]
                );

                $router->get(
                    '/students',
                    [
                        'as'   => 'admin.students.index',
                        'uses' => 'Admin\StudentsController@index',
                    ]
                );

                $router->get(
                    '/students/{uuid}',
                    [
                        'as'   => 'admin.students.show',
                        'uses' => 'Admin\StudentsController@show',
                    ]
                );

                $router->get(
                    '/students/{uuid}/review',
                    [
                        'as'   => 'admin.students.review',
                        'uses' => 'Admin\StudentsController@show',
                    ]
                );

                $router->post(
                    '/students/{uuid}/delete',
                    [
                        'as'   => 'admin.students.delete',
                        'uses' => 'Admin\StudentsController@delete',
                    ]
                );

                $router->get(
                    '/students/{uuid}/personal',
                    [
                        'as'   => 'admin.students.personal.show',
                        'uses' => 'Admin\Students\PersonalController@show',
                    ]
                );

                $router->get(
                    '/students/{uuid}/payment',
                    [
                        'as'   => 'admin.students.payment.index',
                        'uses' => 'Admin\Students\PaymentsController@index',
                    ]
                );

                $router->get(
                    '/students/{uuid}/personal/edit',
                    [
                        'as'   => 'admin.students.personal.edit',
                        'uses' => 'Admin\Students\PersonalController@edit',
                    ]
                );

                $router->post(
                    '/students/{uuid}/personal',
                    [
                        'as'   => 'admin.students.personal.update',
                        'uses' => 'Admin\Students\PersonalController@update',
                    ]
                );

                $router->get(
                    '/students/{uuid}/block',
                    [
                        'as'   => 'admin.students.block',
                        'uses' => 'Admin\StudentsController@block',
                    ]
                );

                $router->get(
                    '/students/{uuid}/unblock',
                    [
                        'as'   => 'admin.students.unblock',
                        'uses' => 'Admin\StudentsController@unblock',
                    ]
                );

                $router->post(
                    '/students/{uuid}/tasks',
                    [
                        'as'   => 'admin.students.tasks.store',
                        'uses' => function (Request $request, $student) {
                            // Student
                            $student = \App\Student::where(
                                'uuid',
                                '=',
                                $student
                            )->firstOrFail();
                            // Task
                            $task = new \App\Task();
                            // Attributes
                            $task->body      = $request->body;
                            $task->category  = $request->category;
                            $task->action_at = strtodate(
                                $request->action_at ?: '+ 2 days'
                            );
                            // Save
                            $student->tasks()->save($task);

                            // Return
                            return redirect()
                                ->route(
                                    'admin.students.personal.show',
                                    [
                                        'uuid' => $student->uuid
                                    ]
                                );
                        }
                    ]
                );

                $router->post(
                    '/students/{uuid}/tasks/{task}',
                    [
                        'as'   => 'admin.students.tasks.update',
                        'uses' => function (Request $request, $student, $task) {
                            // Student
                            $student = \App\Student::where(
                                'uuid',
                                '=',
                                $student
                            )->firstOrFail();
                            // Task
                            $task = $student->tasks()->where(
                                'tasks.id',
                                '=',
                                $task
                            )->firstOrFail();
                            // Attributes
                            $task->body      = $request->body;
                            $task->category  = $request->category;
                            $task->action_at = strtodate(
                                $request->action_at ?: '+ 2 days'
                            );
                            // Save
                            $task->save();

                            // Return
                            return redirect()
                                ->route(
                                    'admin.students.personal.show',
                                    [
                                        'uuid' => $student->uuid
                                    ]
                                );
                        }
                    ]
                );

                $router->delete(
                    '/students/{uuid}/tasks/{task}',
                    [
                        'as'   => 'admin.students.tasks.destroy',
                        'uses' => function (Request $request, $student, $task) {
                            // Student
                            $student = \App\Student::where(
                                'uuid',
                                '=',
                                $student
                            )->firstOrFail();
                            // Task
                            $task = $student->tasks()->where(
                                'tasks.id',
                                '=',
                                $task
                            )->firstOrFail();
                            // Delete
                            $task->delete();

                            // Return
                            return redirect()
                                ->route(
                                    'admin.students.personal.show',
                                    [
                                        'uuid' => $student->uuid
                                    ]
                                );
                        }
                    ]
                );

                $router->put(
                    '/students/{uuid}/notes',
                    [
                        'as'   => 'admin.students.notes.update',
                        'uses' => function (Request $request, $student) {
                            // Student
                            $student = \App\Student::where(
                                'uuid',
                                '=',
                                $student
                            )->firstOrFail();
                            // Notes
                            $note = $student->note ?: new \App\Note();
                            // Attributes
                            $note->body = $request->body;
                            // Save
                            $student->note()->save($note);

                            // Return
                            return redirect()
                                ->route(
                                    'admin.students.personal.show',
                                    [
                                        'uuid' => $student->uuid,
                                    ]
                                );
                        }
                    ]
                );

                $router->get(
                    '/students/{uuid}/relationships',
                    [
                        'as'   => 'admin.students.relationships.index',
                        'uses' => 'Admin\Students\RelationshipsController@index',
                    ]
                );

                $router->get(
                    '/students/{uuid}/jobs',
                    [
                        'as'   => 'admin.students.jobs.index',
                        'uses' => 'Admin\Students\JobsController@index',
                    ]
                );

                $router->get(
                    '/students/{uuid}/jobs/create',
                    [
                        'as'   => 'admin.students.jobs.create',
                        'uses' => 'Admin\Students\JobsController@index',
                    ]
                );

                $router->get(
                    '/students/{uuid}/lessons',
                    [
                        'as'   => 'admin.students.lessons.index',
                        'uses' => 'Admin\Students\LessonsController@index',
                    ]
                );

                $router->get(
                    '/students/{uuid}/lessons/{lessonUuid}/confirm',
                    [
                        'as'   => 'admin.students.lessons.confirm',
                        'uses' => 'Admin\Students\LessonsController@confirm',
                    ]
                );

                $router->get(
                    '/students/{uuid}/lessons/{lessonUuid}/retry',
                    [
                        'as'   => 'admin.students.lessons.retry',
                        'uses' => 'Admin\Students\LessonsController@retry',
                    ]
                );

                $router->get(
                    '/messages',
                    [
                        'as'   => 'admin.messages.index',
                        'uses' => 'Admin\MessagesController@index',
                    ]
                );

                // Jobs

                $router->get(
                    '/jobs',
                    [
                        'as'   => 'admin.jobs.index',
                        'uses' => 'Admin\JobsController@index',
                    ]
                );

                $router->get(
                    '/jobs/create',
                    [
                        'as'   => 'admin.jobs.create',
                        'uses' => 'Admin\JobsController@index',
                    ]
                );

                $router->get(
                    '/jobs/{uuid}',
                    [
                        'as'   => 'admin.jobs.details.edit',
                        'uses' => 'Admin\Jobs\DetailsController@edit',
                    ]
                );

                // Lessons

                $router->get(
                    '/lessons',
                    [
                        'as'   => 'admin.lessons.index',
                        'uses' => 'Admin\LessonsController@index',
                    ]
                );

                $router->get(
                    '/lessons/{uuid}/refund',
                    [
                        'as'   => 'admin.lessons.refund',
                        'uses' => 'Admin\LessonsController@index',
                    ]
                );

                $router->get(
                    '/lessons/{uuid}/cancel',
                    [
                        'as'   => 'admin.lessons.cancel',
                        'uses' => 'Admin\LessonsController@index',
                    ]
                );

                $router->get(
                    '/lessons/{uuid}/edit',
                    [
                        'as'   => 'admin.lessons.edit',
                        'uses' => 'Admin\LessonsController@index',
                    ]
                );

                $router->get(
                    '/students/{uuid}/settings',
                    [
                        'as'   => 'admin.students.settings.show',
                        'uses' => 'Admin\Students\SettingsController@show',
                    ]
                );

                $router->post(
                    '/students/{uuid}/settings',
                    [
                        'as'   => 'admin.students.settings.update',
                        'uses' => 'Admin\Students\SettingsController@update',
                    ]
                );

                $router->get(
                    '/background-checks',
                    [
                        'as'   => 'admin.background_checks.index',
                        'uses' => 'Admin\BackgroundChecksController@index',
                    ]
                );

                //Blog

                $router->get(
                    '/blog',
                    [
                        'as'   => 'admin.blog.index',
                        'uses' => 'Admin\BlogController@index',
                    ]
                );

                $router->get(
                    '/blog/{id}/article',
                    [
                        'as'   => 'admin.blog.article.show',
                        'uses' => 'Admin\Blog\ArticleController@show',
                    ]
                );

                $router->get(
                    '/blog/article/create',
                    [
                        'as'   => 'admin.blog.article.create',
                        'uses' => 'Admin\Blog\ArticleController@create',
                    ]
                );

                $router->post(
                    '/blog/articles',
                    [
                        'as'   => 'admin.blog.article.store',
                        'uses' => 'Admin\Blog\ArticleController@store',
                    ]
                );

                $router->get(
                    '/blog/article/{id}/delete',
                    [
                        'as'   => 'admin.blog.article.delete',
                        'uses' => 'Admin\Blog\ArticleController@destroy',
                    ]
                );

                $router->post(
                    '/blog/article/upload',
                    [
                        'as'   => 'admin.blog.article.upload',
                        'uses' => 'Admin\Blog\ArticleController@upload',
                    ]
                );

                $router->delete(
                    '/blog/article/delete_image',
                    [
                        'as'   => 'admin.blog.article.delete_image',
                        'uses' => 'Admin\Blog\ArticleController@delete_image',
                    ]
                );
                // Transgressions

                $router->get(
                    '/transgressions',
                    [
                        'as'   => 'admin.transgressions.index',
                        'uses' => 'Admin\TransgressionsController@index',

                    ]
                );
         

        $router->get('/relationships/{id}/messages', [
            'as'   => 'admin.relationships.messages.show',
            'uses' => 'Admin\Relationships\MessagesController@show',
        ]);

        $router->post('/relationships/{id}/messages', [
            'as'   => 'admin.relationships.messages.store',
            'uses' => 'Admin\Relationships\MessagesController@store',
        ]);

        $router->get('/relationships/{id}/lessons', [
            'as'   => 'admin.relationships.lessons.index',
            'uses' => 'Admin\Relationships\LessonsController@index',
        ]);

        $router->get('/tutors', [
            'as'   => 'admin.tutors.index',
            'uses' => 'Admin\TutorsController@index',
        ]);

        $router->get('/tutors/{uuid}', [
            'as'   => 'admin.tutors.show',
            'uses' => 'Admin\TutorsController@show',
        ]);

        $router->get('/tutors/{uuid}/delete', [
            'as'   => 'admin.tutors.delete',
            'uses' => 'Admin\TutorsController@delete',
        ]);

        $router->get('/tutors/{uuid}/block', [
            'as'   => 'admin.tutors.block',
            'uses' => 'Admin\TutorsController@block',
        ]);

        $router->get('/tutors/{uuid}/unblock', [
            'as'   => 'admin.tutors.unblock',
            'uses' => 'Admin\TutorsController@unblock',
        ]);

        $router->get('/tutors/{uuid}/personal', [
            'as'   => 'admin.tutors.personal.show',
            'uses' => 'Admin\Tutors\PersonalController@show',
        ]);

        $router->get('/tutors/{uuid}/personal/edit', [
            'as'   => 'admin.tutors.personal.edit',
            'uses' => 'Admin\Tutors\PersonalController@edit',
        ]);

        $router->post('/tutors/{uuid}/personal', [
            'as'   => 'admin.tutors.personal.update',
            'uses' => 'Admin\Tutors\PersonalController@update',
        ]);

        $router->post('/tutors/{uuid}/tasks', [
            'as'   => 'admin.tutors.tasks.store',
            'uses' => function (Request $request, $tutor) {
                // Tutor
                $tutor = \App\Tutor::where('uuid', '=', $tutor)->firstOrFail();
                // Task
                $task = new \App\Task();
                // Attributes
                $task->body      = $request->body;
                $task->category  = $request->category;
                $task->action_at = strtodate($request->action_at);
                $task->action_at = strtodate($request->action_at ?: '+ 2 days');
                // Save
                $tutor->tasks()->save($task);
                // Return
                return redirect()
                    ->route('admin.tutors.personal.show', [
                        'uuid' => $tutor->uuid
                    ]);
            }
        ]);

        $router->post('/tutors/{uuid}/tasks/{task}', [
            'as'   => 'admin.tutors.tasks.update',
            'uses' => function (Request $request, $tutor, $task) {
                // Tutor
                $tutor = \App\Tutor::where('uuid', '=', $tutor)->firstOrFail();
                // Task
                $task = $tutor->tasks()->where('tasks.id', '=', $task)->firstOrFail();
                // Attributes
                $task->body      = $request->body;
                $task->category  = $request->category;
                $task->action_at = strtodate($request->action_at ?: '+ 2 days');
                // Save
                $task->save();
                // Return
                return redirect()
                    ->route('admin.tutors.personal.show', [
                        'uuid' => $tutor->uuid
                    ]);
            }
        ]);

        $router->delete('/tutors/{uuid}/tasks/{task}', [
            'as'   => 'admin.tutors.tasks.destroy',
            'uses' => function (Request $request, $tutor, $task) {
                // Tutor
                $tutor = \App\Tutor::where('uuid', '=', $tutor)->firstOrFail();
                // Task
                $task = $tutor->tasks()->where('tasks.id', '=', $task)->firstOrFail();
                // Delete
                $task->delete();
                // Return
                return redirect()
                    ->route('admin.tutors.personal.show', [
                        'uuid' => $tutor->uuid
                    ]);
            }
        ]);

        $router->put('/tutors/{uuid}/notes', [
            'as'   => 'admin.tutors.notes.update',
            'uses' => function (Request $request, $tutor) {
                // Tutor
                $tutor = \App\Tutor::where('uuid', '=', $tutor)->firstOrFail();
                // Notes
                $note = $tutor->note ?: new \App\Note();
                // Attributes
                $note->body = $request->body;
                // Save
                $tutor->note()->save($note);
                // Return
                return redirect()
                    ->route('admin.tutors.personal.show', [
                        'uuid' => $tutor->uuid,
                    ]);
            }
        ]);

        $router->get('/tutors/{uuid}/relationships', [
            'as'   => 'admin.tutors.relationships.index',
            'uses' => 'Admin\Tutors\RelationshipsController@index',
        ]);

        $router->get('/tutors/{uuid}/billing', [
            'as'   => 'admin.tutors.billing.index',
            'uses' => 'Admin\Tutors\BillingController@index',
        ]);

        $router->get('/tutors/{uuid}/background-check', [
            'as'   => 'admin.tutors.background_check.index',
            'uses' => 'Admin\Tutors\BackgroundCheckController@index',
        ]);

        $router->get('/tutors/{uuid}/background-check/{type}/delete', [
            'as'   => 'admin.tutors.background_check.delete',
            'uses' => 'Admin\Tutors\BackgroundCheckController@index',
        ]);

        $router->get('/tutors/{uuid}/lessons', [
            'as'   => 'admin.tutors.lessons.index',
            'uses' => 'Admin\Tutors\LessonsController@index',
        ]);

        $router->get('/tutors/{uuid}/lessons/create', [
            'as'   => 'admin.tutors.lessons.create',
            'uses' => 'Admin\Tutors\LessonsController@index',
        ]);

        $router->get('/students', [
            'as'   => 'admin.students.index',
            'uses' => 'Admin\StudentsController@index',
        ]);

        $router->get('/students/{uuid}', [
            'as'   => 'admin.students.show',
            'uses' => 'Admin\StudentsController@show',
        ]);

        $router->get('/students/{uuid}/review', [
            'as'   => 'admin.students.review',
            'uses' => 'Admin\StudentsController@show',
        ]);

        $router->post('/students/{uuid}/delete', [
            'as'   => 'admin.students.delete',
            'uses' => 'Admin\StudentsController@delete',
        ]);

        $router->get('/students/{uuid}/personal', [
            'as'   => 'admin.students.personal.show',
            'uses' => 'Admin\Students\PersonalController@show',
        ]);

        $router->get('/students/{uuid}/payment', [
            'as'   => 'admin.students.payment.index',
            'uses' => 'Admin\Students\PaymentsController@index',
        ]);

        $router->get('/students/{uuid}/personal/edit', [
            'as'   => 'admin.students.personal.edit',
            'uses' => 'Admin\Students\PersonalController@edit',
        ]);

        $router->post('/students/{uuid}/personal', [
            'as'   => 'admin.students.personal.update',
            'uses' => 'Admin\Students\PersonalController@update',
        ]);

        $router->get('/students/{uuid}/block', [
            'as'   => 'admin.students.block',
            'uses' => 'Admin\StudentsController@block',
        ]);

        $router->get('/students/{uuid}/unblock', [
            'as'   => 'admin.students.unblock',
            'uses' => 'Admin\StudentsController@unblock',
        ]);

        $router->post('/students/{uuid}/tasks', [
            'as'   => 'admin.students.tasks.store',
            'uses' => function (Request $request, $student) {
                // Student
                $student = \App\Student::where('uuid', '=', $student)->firstOrFail();
                // Task
                $task = new \App\Task();
                // Attributes
                $task->body      = $request->body;
                $task->category  = $request->category;
                $task->action_at = strtodate($request->action_at ?: '+ 2 days');
                // Save
                $student->tasks()->save($task);
                // Return
                return redirect()
                    ->route('admin.students.personal.show', [
                        'uuid' => $student->uuid
                    ]);
            }
        ]);

        $router->post('/students/{uuid}/tasks/{task}', [
            'as'   => 'admin.students.tasks.update',
            'uses' => function (Request $request, $student, $task) {
                // Student
                $student = \App\Student::where('uuid', '=', $student)->firstOrFail();
                // Task
                $task = $student->tasks()->where('tasks.id', '=', $task)->firstOrFail();
                // Attributes
                $task->body      = $request->body;
                $task->category  = $request->category;
                $task->action_at = strtodate($request->action_at ?: '+ 2 days');
                // Save
                $task->save();
                // Return
                return redirect()
                    ->route('admin.students.personal.show', [
                        'uuid' => $student->uuid
                    ]);
            }
        ]);

        $router->delete('/students/{uuid}/tasks/{task}', [
            'as'   => 'admin.students.tasks.destroy',
            'uses' => function (Request $request, $student, $task) {
                // Student
                $student = \App\Student::where('uuid', '=', $student)->firstOrFail();
                // Task
                $task = $student->tasks()->where('tasks.id', '=', $task)->firstOrFail();
                // Delete
                $task->delete();
                // Return
                return redirect()
                    ->route('admin.students.personal.show', [
                        'uuid' => $student->uuid
                    ]);
            }
        ]);

        $router->put('/students/{uuid}/notes', [
            'as'   => 'admin.students.notes.update',
            'uses' => function (Request $request, $student) {
                // Student
                $student = \App\Student::where('uuid', '=', $student)->firstOrFail();
                // Notes
                $note = $student->note ?: new \App\Note();
                // Attributes
                $note->body = $request->body;
                // Save
                $student->note()->save($note);
                // Return
                return redirect()
                    ->route('admin.students.personal.show', [
                        'uuid' => $student->uuid,
                    ]);
            }
        ]);

        $router->get('/students/{uuid}/relationships', [
            'as'   => 'admin.students.relationships.index',
            'uses' => 'Admin\Students\RelationshipsController@index',
        ]);

        $router->get('/students/{uuid}/jobs', [
            'as'   => 'admin.students.jobs.index',
            'uses' => 'Admin\Students\JobsController@index',
        ]);

        $router->get('/students/{uuid}/jobs/create', [
            'as'   => 'admin.students.jobs.create',
            'uses' => 'Admin\Students\JobsController@index',
        ]);

        $router->get('/students/{uuid}/lessons', [
            'as'   => 'admin.students.lessons.index',
            'uses' => 'Admin\Students\LessonsController@index',
        ]);

        $router->get('/students/{uuid}/lessons/{lessonUuid}/confirm', [
            'as'   => 'admin.students.lessons.confirm',
            'uses' => 'Admin\Students\LessonsController@confirm',
        ]);

        $router->get('/students/{uuid}/lessons/{lessonUuid}/retry', [
            'as'   => 'admin.students.lessons.retry',
            'uses' => 'Admin\Students\LessonsController@retry',
        ]);

        $router->get('/messages', [
            'as'   => 'admin.messages.index',
            'uses' => 'Admin\MessagesController@index',
        ]);

        // Jobs

        $router->get('/jobs', [
            'as'   => 'admin.jobs.index',
            'uses' => 'Admin\JobsController@index',
        ]);

        $router->get('/jobs/create', [
            'as'   => 'admin.jobs.create',
            'uses' => 'Admin\JobsController@index',
        ]);

        $router->get('/jobs/{uuid}', [
            'as'   => 'admin.jobs.details.edit',
            'uses' => 'Admin\Jobs\DetailsController@edit',
        ]);

        // Lessons

        $router->get('/lessons', [
            'as'   => 'admin.lessons.index',
            'uses' => 'Admin\LessonsController@index',
        ]);

        $router->get('/lessons/{uuid}/refund', [
            'as'    => 'admin.lessons.refund',
            'uses'  => 'Admin\LessonsController@index',
        ]);

        $router->get('/lessons/{uuid}/cancel', [
            'as'    => 'admin.lessons.cancel',
            'uses'  => 'Admin\LessonsController@index',
        ]);

        $router->get('/lessons/{uuid}/edit', [
            'as'    => 'admin.lessons.edit',
            'uses'  => 'Admin\LessonsController@index',
        ]);

        $router->get('/students/{uuid}/settings', [
            'as'   => 'admin.students.settings.show',
            'uses' => 'Admin\Students\SettingsController@show',
        ]);

        $router->post('/students/{uuid}/settings', [
            'as'   => 'admin.students.settings.update',
            'uses' => 'Admin\Students\SettingsController@update',
        ]);

        $router->get('/background-checks', [
            'as'   => 'admin.background_checks.index',
            'uses' => 'Admin\BackgroundChecksController@index',
        ]);

        //Blog

        $router->get('/blog', [
            'as'   => 'admin.blog.index',
            'uses' => 'Admin\BlogController@index',
        ]);

        $router->get('/blog/{id}/article', [
            'as'   => 'admin.blog.article.show',
            'uses' => 'Admin\Blog\ArticleController@show',
        ]);

        $router->get('/blog/article/create', [
            'as'   => 'admin.blog.article.create',
            'uses' => 'Admin\Blog\ArticleController@create',
        ]);

        $router->post('/blog/articles', [
            'as'   => 'admin.blog.article.store',
            'uses' => 'Admin\Blog\ArticleController@store',
        ]);

        $router->get('/blog/article/{id}/delete', [
            'as'   => 'admin.blog.article.delete',
            'uses' => 'Admin\Blog\ArticleController@destroy',
        ]);

        $router->post('/blog/article/upload', [
            'as'   => 'admin.blog.article.upload',
            'uses' => 'Admin\Blog\ArticleController@upload',
        ]);

        $router->delete('/blog/article/delete_image', [
            'as'   => 'admin.blog.article.delete_image',
            'uses' => 'Admin\Blog\ArticleController@delete_image',
        ]); 
        // Transgressions

        $router->get('/transgressions', [
            'as'   => 'admin.transgressions.index',
            'uses' => 'Admin\TransgressionsController@index',

        ]);

    });
});



/**
 * Api
 */

$router->group([
    'prefix' => '/api'
], function ($router) {
    $router->post('/cookies', function (Request $request) {
        if ($request->key && $request->value) {
            Cookie::queue(Cookie::forever($request->key, $request->value));
        }
    });

    $router->post('/sessions', [
        'as'   => 'api.sessions.create',
        'uses' => 'Api\SessionsController@create',
    ]);

    $router->post('/users', [
        'as'   => 'api.users.create',
        'uses' => 'Api\UsersController@create',
    ]);

    $router->patch('/users/{uuid}', [
        'as'         => 'api.users.edit',
        'uses'       => 'Api\UsersController@edit',
        'middleware' => 'auth.uuid',
    ]);

    $router->get('/users/{uuid}', [
        'as'         => 'api.users.get',
        'uses'       => 'Api\UsersController@get',
    ]);

    $router->delete('/users/{uuid}', [
        'as'         => 'api.users.destroy',
        'uses'       => 'Api\UsersController@destroy',
        'middleware' => 'auth.uuid'
    ]);

    $router->put('/users/{uuid}/toggleblock', [
        'as'         => 'api.users.toggleblock',
        'uses'       => 'Api\UsersController@toggleBlock',
        'middleware' => 'auth.uuid'
    ]);

    $router->get('/users/{uuid}/tutors', [
        'as'         => 'api.users.tutors.get',
        'uses'       => 'Api\Users\TutorsController@get',
        'middleware' => 'auth.uuid'
    ]);

    /*
    $router->patch('/users/{uuid}/profile', [
        'as'         => 'api.users.profile.edit',
        'uses'       => 'Api\Users\ProfileController@edit',
        'middleware' => 'auth.uuid',
    ]);
    */

    $router->post('/users/{uuid}/profile-picture', [
        'as'         => 'api.users.profile-picture.create',
        'uses'       => 'Api\Users\ProfilePictureController@create',
        'middleware' => 'auth.uuid',
    ]);

    $router->post('/users/{uuid}/identity-document', [
        'as'         => 'api.users.identity-document.create',
        'uses'       => 'Api\Users\IdentityDocumentController@create',
        'middleware' => 'auth.uuid',
    ]);

    $router->post('/files/images', [
        'as'         => 'api.files.images.create',
        'uses'       => 'Api\Files\ImageController@create',
    ]);

    /*
    $router->patch('/users/{uuid}/qualification_teacher_status', [
        'as'         => 'api.users.qualification_teacher_status.edit',
        'uses'       => 'Api\Users\QualificationTeacherStatusController@edit',
        'middleware' => 'auth.uuid',
    ]);
     */

    $router->post('/users/{uuid}/qualifications', [
        'as'         => 'api.users.qualifications.create',
        'uses'       => 'Api\Users\QualificationsController@create',
        'middleware' => 'auth.uuid',
    ]);

    $router->post('/users/{uuid}/background-checks', [
        'as'         => 'api.users.background_checks.create',
        'uses'       => 'Api\Users\BackgroundChecksController@create',
    ]);

    $router->post('/users/{uuid}/background-checks/{type}', [
        'as'         => 'api.users.background_check.create',
        'uses'       => 'Api\Users\BackgroundChecks\BackgroundCheckController@create',
    ]);

    $router->put('/users/{uuid}/background-checks/{type}', [
        'as'         => 'api.users.background_check.update',
        'uses'       => 'Api\Users\BackgroundChecks\BackgroundCheckController@update',
    ]);

    $router->delete('/users/{uuid}/background-checks/{type}', [
        'as'         => 'api.users.background_check.delete',
        'uses'       => 'Api\Users\BackgroundChecks\BackgroundCheckController@delete',
    ]);

    $router->put('/users/{uuid}/qualifications/qts', [
        'as'         => 'api.users.qualifications.qts.create',
        'uses'       => 'Api\Users\Qualifications\QTSController@create',
        'middleware' => 'auth.uuid',
    ]);

    $router->post('/users/{uuid}/subjects', [
        'as'         => 'api.users.subjects.create',
        'uses'       => 'Api\Users\SubjectsController@create',
        'middlewear' => 'auth.uuid',
    ]);

    $router->post('/users/{uuid}/reviews', [
        'as'         => 'api.users.reviews.create',
        'uses'       => 'Api\Users\ReviewsController@create',
        'middlewear' => 'auth.student',
    ]);

    $router->get('/users/{uuid}/reviews', [
        'as'         => 'api.reviews',
        'uses'       => 'Api\Users\ReviewsController@index'
    ]);

    $router->get('/reviews/{id}', [
        'as'         => 'api.reviews.get',
        'uses'       => 'Api\Users\ReviewsController@get',
    ]);

    $router->put('/reviews/{id}', [
        'as'         => 'api.reviews.post',
        'uses'       => 'Api\Users\ReviewsController@post',
    ]);

    $router->delete('/reviews/{id}', [
        'as'         => 'api.reviews.delete',
        'uses'       => 'Api\Users\ReviewsController@delete',
    ]);

    $router->post('/users/{uuid}/quiz', [
        'as'         => 'api.users.quiz.submit',
        'uses'       => 'Api\Users\QuizController@submit',
        'middlewear' => 'auth.student',
    ]);

    $router->post('/tutors/{uuid}/lessons/book', [
        'as'         => 'api.tutors.lessons.book',
        'uses'       => 'Api\Tutors\LessonsController@book',
    ]);

    $router->post('/students/{uuid}/jobs/create', [
        'as'         => 'api.students.jobs.create',
        'uses'       => 'Api\Students\JobsController@create',
    ]);

    $router->get('/subjects', [
        'as'   => 'api.subjects.index',
        'uses' => 'Api\SubjectsController@index',
    ]);

    $router->get('/quiz', [
        'as'   => 'api.quiz.index',
        'uses' => 'Api\QuizController@index',
    ]);

    $router->get('/quiz_prep', [
        'as'   => 'api.quiz_prep.index',
        'uses' => 'Api\QuizPrepController@index',
    ]);

    $router->get('/dialogue_routes', [
        'as'   => 'api.basic_dialogue.routes',
        'uses' => 'Api\DialogueController@routes',
    ]);

    $router->get('/basic_user_dialogue/{name}', [
        'as'   => 'api.basic_dialogue.show',
        'uses' => 'Api\DialogueController@show',
    ]);

    $router->post('/user_dialogue_interaction', [
        'as'   => 'api.dialogue_interaction.create',
        'uses' => 'Api\DialogueInteractionController@create',
    ]);

    $router->patch('/user_dialogue_interaction/{id}', [
        'as'   => 'api.dialogue_interaction.update',
        'uses' => 'Api\DialogueInteractionController@update',
    ]);

    $router->post('/messages', [
        'as'   => 'api.messages.create',
        'uses' => 'Api\MessagesController@create',
    ]);

    $router->post('/message_line/{uuid}/flag', [
        'as'   => 'api.message_lines.flag',
        'uses' => 'Api\MessageLinesController@flag',
    ]);

    // Jobs

    $router->get('/jobs', [
        'as'   => 'api.jobs.index',
        'uses' => 'Api\JobsController@index',
    ]);
    $router->get('/jobs/{uuid}', [
        'as'   => 'api.jobs.get',
        'uses' => 'Api\JobsController@get',
    ]);

    $router->put('/jobs/{uuid}', [
        'as'   => 'api.jobs.update',
        'uses' => 'Api\JobsController@update',
    ]);

    $router->delete('/jobs/{uuid}', [
        'as'   => 'api.jobs.delete',
        'uses' => 'Api\JobsController@delete'
    ]);

    $router->put('/jobs/{uuid}/favourite', [
        'as'   => 'api.jobs.favourite',
        'uses' => 'Api\JobsController@favourite',
    ]);

    $router->post('/jobs', [
        'as'   => 'api.jobs.create',
        'uses' => 'Api\JobsController@create',
    ]);

    $router->post('/jobs/{uuid}/message', [
        'as'   => 'api.jobs.message.create',
        'uses' => 'Api\JobsController@createMessage',
    ]);

    // Bookings

    $router->get('/bookings', [
        'as'   => 'api.bookings.store',
        'uses' => 'Api\BookingsController@store',
    ]);

    $router->post('/bookings', [
        'as'   => 'api.bookings.store',
        'uses' => 'Api\BookingsController@store',
    ]);

    $router->get('/bookings/{booking}', [
        'as'   => 'api.bookings.show',
        'uses' => 'Api\BookingsController@show',
    ]);

    $router->put('/bookings/{booking}', [
        'as'   => 'api.bookings.edit',
        'uses' => 'Api\BookingsController@edit',
    ]);

    $router->put('/bookings/{booking}/cancel', [
        'as'   => 'api.bookings.cancel',
        'uses' => 'Api\BookingsController@cancel',
    ]);

    $router->put('/bookings/{booking}/refund', [
        'as'   => 'api.bookings.refund',
        'uses' => 'Api\BookingsController@refund',
    ]);

    $router->put('/bookings/{booking}/confirm', [
        'as'   => 'api.bookings.confirm',
        'uses' => 'Api\BookingsController@confirm',
    ]);

    $router->get('/autocomplete/tutors', [
        'as'   => 'api.autocomplete.tutors',
        'uses' => 'Api\Autocomplete\TutorsController@index',
    ]);

    $router->get('/autocomplete/students', [
        'as'   => 'api.autocomplete.students',
        'uses' => 'Api\Autocomplete\StudentsController@index',
    ]);

    $router->get('/autocomplete/search', [
        'as'   => 'api.autocomplete.search',
        'uses' => 'Api\Autocomplete\SearchController@index',
    ]);

   $router->group([
        'prefix' => '/webhooks'
    ], function ($router) {
        $router->any('/stripe', [
            'as'   => 'api.webhooks.stripe',
            'uses' => 'Api\Webhooks\StripeController@handle',
        ]);
    });
});



/**
 * Sitemap
 */

$router->get(
    '/sitemap.xml',
    [
        'as'   => 'sitemap',
        'uses' => 'SitemapController@index'
    ]
);

