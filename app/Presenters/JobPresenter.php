<?php

namespace App\Presenters;

use App\Job;
use App\MessageLine;
use App\Relationship;
use App\Tutor;
use League\Fractal\TransformerAbstract;

class JobPresenter extends AbstractPresenter
{

    /**
     * @var Tutor|null
     */
    protected $relatedTutor = null;

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'student',
        'location',
        'subject',
        'tutor',
        'replies',
    ];

    /**
     * Create an instance of the presenter
     *
     * @param array $options
     */
    public function __construct(Array $options = [])
    {
        $this->relatedTutor = array_get($options, 'tutor');

        parent::__construct($options);
    }

    /**
     * Turn this object into a generic array
     *
     * @param  Job $job
     *
     * @return array
     */
    public function transform(Job $job)
    {
        $statusTitle = $job->status ? trans('jobs.statuses')[$job->status] : '';

        $result = [
            'uuid'          => (string) $job->uuid,
            'statusTitle'   => (string) $statusTitle,
            'message'       => (string) $job->message,
            'shortMessage'  => str_limit($job->message, 200, '...'),
            'distance'      => $job->distance ? round($job->distance * 1.2, 1) : null,
            'repliesNumber' => $job->getAppliedTutorsCount(),
            'closedFor'     => $job->closed_for,
            'timeOpen'      => $job->opened_at ? $this->formatHumanTime($job->opened_at) : null,
            'isConfirmed'   => $job->isConfirmed(),
            'isConfirmedByApplicant'   => $job->isConfirmedByApplicant()
        ];

        return $result;
    }

    /**
     * Include the student
     *
     * @param  Job $job
     *
     * @return Item
     */
    protected function includeStudent(Job $job)
    {
        return $this->item($job->user, new StudentPresenter());
    }

    /**
     * Include the tutor information
     *
     * @param  Job $job
     *
     * @return Item|null
     */
    protected function includeTutor(Job $job)
    {
        $tutor = $this->relatedTutor;

        if(!$tutor) { return null;}

        $student      = $job->user;
        $relationship = $tutor->relationships()
            ->where('student_id', '=', $student->id)
            ->where('status', '<>', Relationship::REQUESTED_BY_TUTOR)
            ->with('message')
            ->first();

        $related         = $relationship ? true : false;
        $relationMessage = $related ? $relationship->message : null;
        $relMessageUuid  = $relationMessage ? $relationMessage->uuid : null;

        $jobTutor = $job->tutors()->find($tutor->id);

        $applicationMessageLine = $this->getApplicationMessageLine($job, $tutor);

        return $this->item($jobTutor, function(Tutor $tutor = null) use ($job, $related, $relMessageUuid, $applicationMessageLine) {

            $favourited   = $tutor && $tutor->pivot->favourite ?: false;
            $applied      = $tutor && $tutor->pivot->applied ?: false;

            return [
                'favourite'          => $favourited,
                'applied'            => $applied,
                'related'            => $related,
                'relMessageUuid'     => $relMessageUuid,
                'applicationMessage' => $applicationMessageLine ? $applicationMessageLine->body : null,
            ];
        });
    }

    /**
     * @param Job $job
     * @param Tutor $tutor
     */
    protected function getApplicationMessageLine(Job $job, Tutor $tutor)
    {
        $messageLine = $job->messageLines()->where('user_id', '=', $tutor->id)->first();

        return $messageLine;
    }

    /**
     * Include the location
     *
     * @param  Job $job
     *
     * @return Item
     */
    protected function includeLocation(Job $job)
    {
        $location = $job->locations()->first();
        return $this->item($location, new LocationPresenter());
    }

    /**
     * Include the subject
     *
     * @param  Job $job
     *
     * @return Item
     */
    protected function includeSubject(Job $job)
    {
        $subject = $job->subject;

        if(!$subject) {
            return null;
        }

        return $this->item($subject, new SubjectPresenter());
    }

    /**
     * Include replies number
     *
     * @param  Job $job
     *
     * @return Item
     */
    protected function includeReplies(Job $job)
    {
        return $this->collection($job->messageLines, function(MessageLine $messageLine){
            return [
                'body' => $messageLine->body,
                'relationship_status' => $messageLine->message->relationship->is_confirmed
            ];
        });
    }

}
