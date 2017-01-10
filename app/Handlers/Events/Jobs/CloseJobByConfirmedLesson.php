<?php

namespace App\Handlers\Events\Jobs;

use App\Job;
use App\Lesson;
use App\Relationship;
use App\Events\LessonWasConfirmed;
use App\Handlers\Events\EventHandler;
use App\Repositories\Contracts\JobRepositoryInterface;

class CloseJobByConfirmedLesson extends EventHandler
{
    /**
     * @var JobRepositoryInterface
     */
    protected $jobs;

    /**
     * Create the event handler.
     *
     * @param JobRepositoryInterface $jobs
     */
    public function __construct(
        JobRepositoryInterface $jobs
    ) {
        $this->jobs = $jobs;
    }

    /**
     * Handle the event.
     *
     * @param  LessonWasConfirmed $event
     *
     * @return void
     */
    public function handle(LessonWasConfirmed $event)
    {
        // Lookups

        /** @var Lesson $lesson */
        $lesson = $event->lesson;

        /** @var Relationship $relationship */
        $relationship = $lesson->relationship;

        $student = $relationship->student;
        $tutor   = $relationship->tutor;

        $jobs    = $this->jobs->getByTutorAndStudent($student, $tutor);

        if ($jobs->count() == 0) {return;}

        if($jobs->count() > 1) {return;}

        $job = $jobs->first();

        $job = Job::makeClosed($job, Job::CLOSED_FOR_CONFIRMATION);

        $this->dispatchFor($job);

        // Save
        $this->jobs->save($job);
    }

}
