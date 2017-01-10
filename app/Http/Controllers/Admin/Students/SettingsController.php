<?php

namespace App\Http\Controllers\Admin\Students;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Commands\UpdateUserCommand;
use App\Presenters\StudentPresenter;
use App\Http\Controllers\Controller;
use App\Validators\Exceptions\ValidationException;
use App\Database\Exceptions\DuplicateResourceException;
use App\Repositories\Contracts\StudentRepositoryInterface;

class SettingsController extends Controller
{
    /**
     * @var StudentRepositoryInterface
     */
    protected $students;

    /**
     * Create an instance of this controller
     *
     * @param  StudentRepositoryInterface $students
     * @return void
     */
    public function __construct(
        StudentRepositoryInterface $students
    ) {
        $this->students = $students;
    }

    /**
     * Show the specified resource.
     *
     * @param  string $uuid
     * @return Response
     */
    public function show($uuid)
    {
        // Lookup
        $student = $this->students->findByUuid($uuid);

        // Abort
        if ( ! $student) {
            abort(404);
        }

        // Present        
        $student = $this->presentItem(
            $student,
            new StudentPresenter(),
            [
                'include' => [
                    'settings'
                ],
            ]
        );

        // Return
        return view ('admin.students.settings.show', compact('student'));
    }

    /**
     * Update the specified resource
     *
     * @param  Request $request
     * @param  string  $uuid
     * @return Response
     */
    public function update(Request $request, $uuid)
    {
        try {
            $data      = $request->all();
            $data['uuid']      = $uuid;

            $student = $this->dispatchFromArray(UpdateUserCommand::class, $data);
            
            return redirect()
                ->route('admin.students.settings.show', ['uuid' => $student->uuid]);
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $e->getErrorBag());
        } 
    }
}
