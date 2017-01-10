<?php

namespace App\Http\Controllers\Admin\Tutors;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Presenters\TutorPresenter;
use App\Commands\UpdateUserCommand;
use App\Http\Controllers\Controller;
use App\Geocode\Exceptions\NoResultException;
use App\Validators\Exceptions\ValidationException;
use App\Database\Exceptions\DuplicateResourceException;
use App\Repositories\Contracts\TutorRepositoryInterface;

class BillingController extends Controller
{
    /**
     * @var TutorRepositoryInterface
     */
    protected $tutors;

    /**
     * Create an instance of the controller
     *
     * @param  TutorRepositoryInterface $tutors
     * @return void
     */
    public function __construct(
        TutorRepositoryInterface $tutors
    ) {
        $this->tutors = $tutors;
    }

    /**
     * Show the specified resource.
     *
     * @param  string $uuid
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index($uuid)
    {
        // Lookup
        $tutor = $this->tutors->findByUuid($uuid);

        // Abort
        if ( ! $tutor) {
            abort(404);
        }

        // Present
        $tutor = $this->presentItem(
            $tutor,
            new TutorPresenter(),
            [
                'include' => [
                    'requirements',
                    'background_checks',
                    'identity_document',
                ],
            ]
        );

        // Return
        return view ('admin.tutors.billing.index', compact('tutor'));
    }

}
