<?php

namespace App\Presenters;

use App\User;
use App\Tutor;
use App\Relationship;
use App\LessonBooking;
use App\UserBackgroundCheck;
use League\Fractal\ParamBag;

class TutorPresenter extends UserPresenter
{
    protected $data = [];

    public function __construct(Array $options = [], Array $data = [])
    {
        parent::__construct($options);
        $this->data = $data;
    }

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'actions',
        'lessons',
        'profile',
        'subjects',
        'addresses',
        'qualifications',
        'background_checks',
        'identity_document',
        'reviews',
    ];

    /**
     * Turn this object into a generic array
     *
     * @param  User $user
     * @return array
     */
    public function transform(User $user)
    {
        $array = parent::transform($user);

        $array['distance'] = $user->distance ? round($user->distance*1.2, 1) : null;
        $array['score']    = $user->score ?: null;

        return $array;
    }

    /**
     * Include actions
     *
     * @param  Tutor $tutor
     * @return Item
     */
    protected function includeActions(Tutor $tutor)
    {
        $presenter = new ActionsPresenter();
        if(isset($this->data['student'])) {
            $presenter->student = $this->data['student'];
        }

        return $this->item($tutor, $presenter);
    }

    /**
     * Include user profile data
     *
     * @param  Tutor $tutor
     * @return Item
     */
    protected function includeProfile(Tutor $tutor)
    {

        return $this->item($tutor->profile, new ProfilePresenter());
    }

    /**
     * Include qualifications data
     *
     * @param  Tutor $tutor
     * @return Item
     */
    protected function includeQualifications(Tutor $tutor)
    {
        return $this->item([
            'universities' => $tutor->qualificationUniversities,
            'alevels'      => $tutor->qualificationAlevels,
            'others'       => $tutor->qualificationOthers,
            'qts'          => $tutor->qualificationTeacherStatus,
        ], new QualificationsPresenter());
    }

    /**
     * Include subjects data
     *
     * @param  Tutor    $tutor
     * @return Item
     */
    protected function includeSubjects(Tutor $tutor)
    {
       
        if (empty($this->data) ) {
            return $this->collection(
                $tutor->subjects->toTree(),
                new SubjectPresenter()
            );
        }
        
        if (! empty($this->data) && isset($this->data['searchSubject'])) {
            
            // Reorder list of subjects based on search subject
            $searchSubject = $this->data['searchSubject']->id;

            $sorted = $tutor->subjects->sortBy(function ($subject) use ($searchSubject) {
            
                if ($subject->id == $searchSubject || $subject->parent_id == $searchSubject) {
                    return 0;
                } else {
                    return $subject->id;
                }
            });

            $subjects = $sorted->toTree()->values()->all();

            return $this->collection(
                $subjects,
                new SubjectPresenter()
            );
        }

    }

    /**
     * Include background checks data
     *
     * @param  Tutor $tutor
     *
     * @return Item
     */

    protected function includeBackgroundChecks(Tutor $tutor)
    {
        return $this->item($tutor, new TutorBackgroundChecksPresenter());
    }

    /**
     * Include identity document data
     *
     * @param  Tutor $tutor
     * @return Item
     */
    protected function includeIdentityDocument(Tutor $tutor)
    {
        if ($tutor->identityDocument) {
            return $this->item(
                $tutor->identityDocument,
                new IdentityDocumentPresenter()
            );
        }
    }

    /**
     * Inlcude review data.
     *
     * @param  Tutor $tutor
     * @return Collection
     */
    protected function includeReviews(Tutor $tutor)
    {
        return $this->collection($tutor->reviews, new ReviewPresenter());
    }

}
