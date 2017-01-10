<?php namespace App\Transformers;

use App\User;
use App\Tutor;
use App\Address;
use App\UserBackgroundCheck;
use League\Fractal\ParamBag;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use App\Transformers\SubjectsTransformer;

class TutorTransformer extends TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'addresses',
        'private',
        'profile',
        'qualifications',
        'qts',
        'background_checks',
        'subjects',
        'students',
        'identity_document',
        'requirements',
    ];

    public function __construct($options = [])
    {
        if (($includes = array_get($options, 'include', false)) !== false) {
            $this->defaultIncludes = $includes;
        }
    }

    /**
     * Turn this object into a generic array
     *
     * @return array
     */
    public function transform(User $tutor)
    {
        return [
            'account'    => 'tutor',
            'blocked'    => (bool) !empty($tutor->blocked_at),
            'uuid'       => (string)  $tutor->uuid,
            'first_name' => (string)  str_name($tutor->first_name),
            'last_name'  => (string)  str_name(substr($tutor->last_name, 0, 1)),
            'distance'   => is_numeric($tutor->distance)
                ? round($tutor->distance, 2)
                : $tutor->distance,
        ];
    }

    protected function includePrivate(Tutor $tutor)
    {
        return $this->item($tutor, function ($tutor) {
            return [
                'last_name'        => (string)  str_name($tutor->last_name),
                'legal_first_name' => (string) $tutor->legal_first_name,
                'legal_last_name'  => (string) $tutor->legal_last_name,
                'email'            => (string)  $tutor->email,
                'telephone'        => (string)  $tutor->telephone,
                'last_four'        => $tutor->last_four ? (integer) $tutor->last_four : null,
                'dob'              => [
                    'day'   => $tutor->dob ? $tutor->dob->format('d') : null,
                    'month' => $tutor->dob ? $tutor->dob->format('m') : null,
                    'year'  => $tutor->dob ? $tutor->dob->format('Y') : null,
                ],
                'rate'             =>  $tutor->profile ? $tutor->profile->rate : null
            ];
        });
    }

    protected function includeProfile(Tutor $tutor)
    {
        return $this->item($tutor->profile, new ProfileTransformer());
    }

    protected function includeAddresses(Tutor $tutor, ParamBag $params = null)
    {
        $options = ! $params ? [] : [
            'only' => $params->get('only'),
        ];

        return $this->item($tutor->addresses, new AddressesTransformer($options));
    }

    protected function includeSubjects(User $tutor)
    {
        return $this->collection($tutor->subjects, function ($subject) {
            return [
                'id'        => $subject->id,
                'parent_id' => $subject->parent_id,
                'name'      => $subject->name,
                'title'     => $subject->title,
                'path'      => $subject->path,
            ];
        });
    }

    protected function includeStudents(Tutor $tutor)
    {
        return $this->collection($tutor->students, new TutorStudentsTransformer($tutor));
    }

    protected function includeQualifications(Tutor $tutor)
    {
        return $this->item($tutor, new QualificationsTransformer());
    }

    protected function includeQTS(Tutor $tutor)
    {
        return $this->item($tutor->qualificationTeacherStatus, function ($qts) {
            return [
                'level' => $qts ? $qts->level : null,
            ];
        });
    }

    protected function includeBackgroundChecks(Tutor $tutor)
    {
        return $this->item($tutor, new TutorBackgroundChecksTransformer());
    }

    protected function includeIdentityDocument(Tutor $tutor)
    {
        if ($tutor->identityDocument) {
            return $this->item($tutor->identityDocument, function ($identityDocument) {
                return [
                    'uuid'        => $identityDocument->uuid,
                    'is_verified' => $identityDocument->status === 'verified',
                    'status'      => $identityDocument->status,
                    'details'     => $identityDocument->details,
                    'src'         => route('identity-document.show', [
                        'uuid' => $identityDocument->uuid,
                        'ext'  => $identityDocument->ext,
                    ]),
                ];
            });
        }
    }

    protected function includeRequirements(Tutor $tutor)
    {
        return $this->collection($tutor->requirements, new UserRequirementTransformer());
    }

}
