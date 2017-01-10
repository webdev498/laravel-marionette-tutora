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

class PersonalController extends Controller
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
     * @return Response
     */
    public function show($uuid)
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
                    'addresses',
                    'profile',
                    'admin',
                    'requirements',
                    'background_checks',
                    'identity_document',
                    'tasks',
                    'note',
                ],
            ]
        );
        // Return
        return view ('admin.tutors.personal.show', compact('tutor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $uuid
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid)
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
                    'profile',
                    'addresses',
                    'background_checks',
                ],
            ]
        );
        // Return
        return view ('admin.tutors.personal.edit', compact('tutor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $uuid
     * @return \Illuminate\Http\Response
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
            $tutor = $this->dispatchFromArray(UpdateUserCommand::class, $data);

            return redirect()
                ->route('admin.tutors.personal.show', ['uuid' => $tutor->uuid]);
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $e->getErrorBag());
        } catch (CardExceptionInterface $e) {
            
        } catch (NoResultException $e) {
            
        } catch (DuplicateResourceException $e) {
            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
