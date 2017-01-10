<?php namespace App\Transformers;

use App\Student;
use League\Fractal\TransformerAbstract;

class StudentTransformer extends TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'addresses',
        'private',
    ];

    /**
     * Turn this object into a generic array
     *
     * @return array
     */
    public function transform(Student $student)
    {
        $firstName = str_name($student->first_name);
        $lastName  = str_name(substr($student->last_name, 0, 1));

        return [
            'account'    => 'student',
            'uuid'       => (string) $student->uuid,
            'first_name' => (string) $firstName,
            'last_name'  => (string) substr($lastName, 0, 1),
            'name'       => (string) "{$firstName} {$lastName}",
        ];
    }

    protected function includePrivate(Student $student)
    {
        return $this->item($student, function ($student) {
            return [
                'last_name' => (string)  $student->last_name,
                'email'     => (string)  $student->email,
                'telephone' => (string)  $student->telephone,
                'last_four' => $student->last_four ? (integer) $student->last_four : null,
                'dob'       => [
                    'day'   => $student->dob ? $student->dob->format('d') : null,
                    'month' => $student->dob ? $student->dob->format('m') : null,
                    'year'  => $student->dob ? $student->dob->format('Y') : null,
                ],
            ];
        });
    }

    protected function includeAddresses(Student $student, ParamBag $params = null)
    {
        $options = ! $params ? [] : [
            'only' => $params->get('only'),
        ];

        return $this->item($student->addresses, new AddressesTransformer($options));
    }
}
