<?php namespace App\Transformers;

use App\User;
use App\Tutor;
use App\Job;
use Carbon\Carbon;
use App\MessageLine;
use App\Relationship;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use App\Messaging\Parser as MessageLineParser;

class JobTransformer extends AbstractTransformer
{

    /**
     * @var Tutor|null
     */
    protected $relatedTutor = null;

    /**
     * @var User|null
     */
    protected $auth = null;

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
        'initialTutorMessage',
    ];

    /**
     * Create an instance of the presenter
     *
     * @param array $options
     */
    public function __construct(Array $options = [])
    {
        $this->relatedTutor = array_get($options, 'tutor');
        $this->auth         = array_get($options, 'auth');
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

        return [
            'uuid'          => (string) $job->uuid,
            'status'        => (int) $job->status,
            'statusTitle'   => (string) $statusTitle,
            'message'       => (string) $job->message,
            'closed_for'    => (string) $job->closed_for,
            'distance'      => $job->distance ? round($job->distance * 1.2, 1) : null,
            'repliesNumber' => $job->messageLines()->where('message_lines.user_id', '!=', $job->user_id)->count(),
            'by_request'    => (int) $job->by_request
        ];
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
        $includes = [];

        if($this->auth && $this->auth->isAdmin()) {
            $includes = [
                'private'
            ];
        }

        $studentTransformer = new StudentTransformer();

        return $this->item($job->user, $studentTransformer->setDefaultIncludes($includes));
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
        return $this->item($job->locations()->first(), new LocationTransformer());
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

        return $this->item($subject, new SubjectTransformer());
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
        $messageLines = $job
            ->messageLines()
            ->where('message_lines.user_id', '!=', $job->user_id)
            ->get();

        return $this->collection($messageLines, function(MessageLine $messageLine){
            $user = $messageLine->user;

            return [
                'tutor_uuid' => $user->uuid,
                'tutor_name' => $user->first_name .' '. $user->last_name,
                'body'       => $messageLine->body,
                'created_at' => $messageLine->created_at->format('Y-m-d H:m:s'),
                'relationship_status' => $messageLine->message->relationship->is_confirmed
            ];
        });
    }

    /**
     * Include initial tutor message
     *
     * @param  Job $job
     *
     * @return Item
     */
    protected function includeInitialTutorMessage(Job $job)
    {
        $messageLine = $job
            ->messageLines()
            ->where('message_lines.user_id', '=', $job->user_id)
            ->first();

        return $this->item($messageLine, function(MessageLine $messageLine = null){
            if(!$messageLine) {return [];}

            $tutor = $messageLine->message->relationship->tutor;

            return [
                'tutor_uuid' => $tutor->uuid,
                'tutor_name' => $tutor->first_name .' '. $tutor->last_name,
                'body'       => $messageLine->body,
                'created_at' => $messageLine->created_at->format('Y-m-d H:m:s'),
                'relationship_status' => $messageLine->message->relationship->is_confirmed
            ];
        });
    }

}
