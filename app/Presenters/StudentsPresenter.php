<?php namespace App\Presenters;

use App\Lesson;
use App\Student;
use League\Fractal\TransformerAbstract;
use App\Transformers\AddressesTransformer;

class StudentsPresenter extends TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'addresses',
        'note',
        'task',
        'private',
    ];

    /**
     * Create an instance of this presenter.
     *
     * @param  Array $options
     * @return void
     */
    public function __construct(Array $options = [])
    {
        if (($includes = array_get($options, 'include', false)) !== false) {
            $this->defaultIncludes = array_extend($this->defaultIncludes, $includes);
        }
    }

    /**
     * Turn this object into a generic array
     *
     * @return array
     */
    public function transform(Student $student)
    {
        return [
            'uuid'        => (string) $student->uuid,
            'email'       => $student->email,
            'telephone'   => $student->telephone,
            'created_at'  => (string) $student->created_at,
            'updated_at'  => (string) $student->updated_at,
            'name'        => (string) $this->name($student),
            'first_name'  => (string) $student->first_name,
            'last_name'   => (string) $student->last_name,
            'lesson'      => $this->lesson($student),
        ];
    }

    protected function name(Student $student)
    {
        return $student->first_name.' '.$student->last_name;
    }

    protected function lesson(Student $student)
    {
        $lesson = $student->lessons->first();

        if ($lesson === null) {
            return false;
        }

        $booking = $lesson->bookings->first();

        if ($booking === null) {
            return false;
        }

        return [
            'subject' => (string) $lesson->subject->title,
            'date'    => [
                'short' => (string) $booking->start_at->format('jS M'),
                'long'  => (string) $booking->start_at->format('jS F, Y'),
            ],
            'time' => [
                'start'  => (string) $booking->start_at->format('H:i'),
                'finish' => (string) $booking->finish_at->format('H:i'),
            ],
            'duration' => (string) gmdate('H:i', $booking->duration),
            'rate'     => '&pound;'.$booking->rate,
            'price'    => '&pound;'.$booking->price,
        ];
    }

    protected function includeAddresses(Student $student, ParamBag $params = null)
    {
        $options = ! $params ? [] : [
            'only' => $params->get('only'),
        ];

        return $this->item($student->addresses, new AddressesTransformer($options));
    }

    protected function includeNote($student)
    {
        return $this->item($student->note, function ($note) {
            return [
                'body' => $note ? $note->body : null,
            ];
        });
    }

    protected function includeTask($student)
    {
        return $this->item($student->tasks->first(), function ($task) {
            return [
                'description' => $task ? $task->description : null,
                'due_at' => $task ? $task->due_at : null,
            ];
        });
    }

    protected function includePrivate(Student $student)
    {
        return $this->item($student, function ($student) {
            return [
                'last_four' => (integer) $student->last_four,
                'billing_id' => $student->billing_id,
                'dob'       => $student->dob
                    ? $student->dob->format('d/m/Y')
                    : null,
            ];
        });
    }
}
