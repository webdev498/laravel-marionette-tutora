<?php namespace App\Mailers;

use App\Presenters\StudentPresenter;
use App\Presenters\TutorPresenter;
use App\Search;
use App\Student;
use App\UserSubscription;
use Illuminate\Database\Eloquent\Collection;

class StudentMarketingMailer extends AbstractMailer
{
	/**
     * DefaultStudentMarketingEmail
     *
     * @param  Student         $student
     * @return void
     */
    public function studentMarketingDefault(
        Student         $student,
        Collection      $tutors
    ) { 

        $subject = $this->subjectLineWithoutSearch();

        $view    = 'emails.student-marketing.default-marketing';
        $list    = UserSubscription::MARKETING;
        $data    = [
            'student' => $this->presentItem($student, new StudentPresenter(), [
                'include' => [
                    'private',
                    'admin',
                ]
            ]),
            'tutors'    => $this->presentCollection($tutors, new TutorPresenter(), [
                'include'   => [
                    'profile'
                ]
            ]) 
        ];

        return $this->sendToUser($student, $subject, $view, $data, $list);
    }
   
    /**
     * Student marketing with Suggestions
     *
     * @param  Student         $student
     * @param  Search          $search
     * @param  Tutors          $tutors
     * @return void
     */
    public function studentMarketingWithSuggestions(
        Student         $student,
        Search          $search,
        Collection      $tutors
    ) { 

        $subject = $this->subjectLineGenerator($search);

        $view    = 'emails.student-marketing.with-suggestions';
        $list    = UserSubscription::MARKETING;
        $data    = [
            'student' => $this->presentItem($student, new StudentPresenter(), [
                'include' => [
                    'private',
                    'admin',
                ]
            ]),
            'search'    => $search,
            'tutors'    => $this->presentCollection($tutors, new TutorPresenter(), [
                'include'   => [
                    'profile'
                ]
            ])
        ];
        

        return $this->sendToUser($student, $subject, $view, $data, $list);
    }
   

    protected function subjectLineGenerator(Search $search)
    {

        if ($search->subject) {
            $subject = $search->subject->title;
        } else {
            $subject = null;
        }

        $city = $search->city;

        if ($subject && $city)
        {
            return $this->subjectLineWithSearch($subject, $city);
        }

        if ($subject && ! $city)
        {
            return $this->subjectLineWithSubject($subject);
        }

        if (! $subject && $city)
        {
            return $this->subjectLineWithCity($city);
        }


        if (! $subject && ! $city)
        {
            return $this->subjectLineWithoutSearch();
        }
    }

    protected function subjectLineWithSearch($subject, $city)
    {
        $lines = [
            "Still looking for a $subject tutor? | Tutora",
            "Top $subject Tutors in $city | Tutora",
            "These are our most popular $subject Tutors for a reason | Tutora",
            "Find out why these are our most popular $subject tutors in $city | Tutora"
        ];

        return $lines[array_rand($lines)];
    }

    protected function subjectLineWithSubject($subject)
    {
        $lines = [
            "Still looking for a $subject tutor? | Tutora",
            "Top $subject Tutors | Tutora",
            "These are our most popular $subject Tutors for a reason | Tutora",
            "Find out why these are our most popular $subject tutors | Tutora"
        ];

        return $lines[array_rand($lines)];
    }

    protected function subjectLineWithCity($city)
    {
        $lines = [
            "Still looking for a tutor in $city | Tutora",
            "The Top Tutors in $city | Tutora",
            "These are our most popular Tutors in $city for a reason | Tutora",
            "Find out why these are our most popular $city tutors | Tutora"
        ];

        return $lines[array_rand($lines)];
    }

    protected function subjectLineWithoutSearch()
    {
        $lines = [
            "Still looking for a private tutor? | Tutora",
            "These are the best Private Tutors in the UK | Tutora",
            "These are our most popular Private Tutors for a reason | Tutora",
            "Our Most Recommended Private Tutors | Tutora"
        ];

        return $lines[array_rand($lines)];
    }

}