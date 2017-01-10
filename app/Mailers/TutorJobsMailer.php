<?php namespace App\Mailers;

use App\Presenters\TutorPresenter;
use App\Presenters\JobPresenter;
use App\Search;
use App\Tutor;
use App\UserSubscription;
use Illuminate\Database\Eloquent\Collection;

class TutorJobsMailer extends AbstractMailer
{
    /**
     * @param  Tutor         $tutor
     * @param  Collection    $jobs
     */
    public function newJobsForTutor(
        Tutor         $tutor,
        Collection    $jobs
    ) {

        $subject = $this->subjectLine($jobs->count());

        $view    = 'emails.tutor-marketing.new-jobs';
        $list    = UserSubscription::JOBS;
        $data    = [
            'tutor' => $this->presentItem($tutor, new TutorPresenter(), [
                'include' => [
                    'private',
                    'admin',
                ]
            ]),
            'jobs'  => $this->presentCollection($jobs, new JobPresenter(), [
                'include' => [
                    'subject',
                    'location',
                ]
            ])
        ];
        

        $this->sendToUser($tutor, $subject, $view, $data, $list);
    }

    protected function subjectLine($count)
    {
        if ($count == 1) {
            $lines = [
                "$count New Tuition Job In Your Area | Tutora",
            ];   
        } else {
             $lines = [
                "$count New Tuition Jobs In Your Area | Tutora",
            ];
        }


        return $lines[array_rand($lines)];
    }

}