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

class PersonalController extends Controller
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
                    'addresses',
                    'tasks',
                    'note',
                    'searches',
                    'searches.subject',
                    'settings'
                ],
            ]
        );
        // Return
        return view ('admin.students.personal.show', compact('student'));
    }

    /**
     * Edit the specified resource
     *
     * @param  String $uuid
     * @return Response
     */
    public function edit($uuid)
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
                    'addresses',
                ],
            ]
        );
        // Return
        return view ('admin.students.personal.edit', compact('student'));
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
            $addresses = [];
            foreach ((array) $data['addresses'] as $name => $attributes) {
                foreach ($attributes as $attribute) {
                    if (empty($attribute)) {
                        continue 2;
                    }

                    $addresses[$name] = $attributes;
                }
            }

            $data['addresses'] = $addresses ?: null;
            $data['uuid']      = $uuid;

            $student = $this->dispatchFromArray(UpdateUserCommand::class, $data);
            
            return redirect()
                ->route('admin.students.personal.show', ['uuid' => $student->uuid]);
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $e->getErrorBag());
        } catch (CardExceptionInterface $e) {
            dd($e);
        } catch (NoResultException $e) {
            dd($e);
        } catch (DuplicateResourceException $e) {
            dd($e);
        }
    }
}
