<?php

namespace App\Presenters;

use App\Relationship;
use League\Fractal\TransformerAbstract;

class RelationshipPresenter extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'tutor',
        'student',
        'message',
        'lessons',
        'tasks',
        'note',
        'searches',
    ];

    /**
     * Turn this object into a generic array
     *
     * @param  Relationship $relationship
     * @return array
     */
    public function transform(Relationship $relationship)
    {
        return [
            'id'        => (integer) $relationship->id,
            'rate'      => (integer) $relationship->rate,
            'status'    => (string)  $relationship->status,
            'is_confirmed' => (boolean) $relationship->is_confirmed,
            'is_application' => (boolean) $relationship->is_application,
            'reason'    => (string)  $relationship->reason
        ];
    }

    /**
     * Include the tutor
     *
     * @param  Relationship $relationship
     * @return Item
     */
    protected function includeTutor(Relationship $relationship)
    {
        return $this->item($relationship->tutor, new TutorPresenter());
    }

    /**
     * Include the student
     *
     * @param  Relationship $relationship
     * @return Item
     */
    protected function includeStudent(Relationship $relationship)
    {
        return $this->item($relationship->student, new StudentPresenter());
    }

    /**
     * Include the message
     *
     * @param  Relationship $relationship
     * @return Item
     */
    protected function includeMessage(Relationship $relationship)
    {
        if ($relationship->message) {
            return $this->item($relationship->message, new MessagePresenter());
        }
    }

    /**
     * Include lessons data
     *
     * @param  Relationship $relationship
     * @return Collection
     */
    protected function includeLessons(Relationship $relationship)
    {
        return $this->collection($relationship->lessons, new LessonPresenter());
    }

    /**
     * Include the tasks
     *
     * @param  Relationship $relationship
     * @return Collection
     */
    protected function includeTasks(Relationship $relationship)
    {
        return $this->collection($relationship->tasks, new TaskPresenter());
    }

    /**
     * Include the note
     *
     * @param  Relationship $relationship
     * @return Collection
     */
    protected function includeNote(Relationship $relationship)
    {
        $note = $relationship->note ?: new \App\Note();
        return $this->item($note, new NotePresenter());
    }

    /**
     * Include the searches
     *
     * @param  Relationship $relationship
     * @return Collection
     */
    protected function includeSearches(Relationship $relationship)
    {
        return $this->collection($relationship->searches, new SearchesPresenter());
    }
}
