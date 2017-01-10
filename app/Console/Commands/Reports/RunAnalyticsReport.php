<?php namespace App\Console\Commands\Reports;

use App\Console\Commands\Command;
use App\LessonBooking;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;
use App\Repositories\Contracts\RelationshipRepositoryInterface;
use App\Repositories\Contracts\SearchRepositoryInterface;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\Contracts\SubjectRepositoryInterface;
use App\Repositories\Contracts\TutorRepositoryInterface;
use App\UserProfile;
use Carbon\Carbon;
use DB;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Builder;

class RunAnalyticsReport extends Command
{

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'tutora:run_analytics_report {period} {offset?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analytics Report';

    /**
     * @var Database
     */
    protected $database;
   
   /**
     * @var MessageRepositoryInterface
     */
    protected $messages;

    /**
     * @var LessonBookingRepositoryInterface
     */
    protected $bookings;

    /**
     * @var TutorRepositoryInterface
     */
    protected $tutors;
    
    /**
     * @var RelationshipRepositoryInterface
     */
    protected $relationships;
    
    /**
     * @var StudentRepositoryInterface
     */
    protected $students;

    /**
     * @var SearchRepositoryInterface
     */
    protected $searches;

    /**
     * @var SubjectRepositoryInterface
     */
    protected $subjects;
    
    /**
     * @var DateTime $start
     */
    protected $start;

    /**
     * @var DateTime $end
     */
    protected $end;
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        DatabaseManager                     $database,
        LessonBookingRepositoryInterface   $bookings,
        TutorRepositoryInterface $tutors,
        RelationshipRepositoryInterface $relationships,
        StudentRepositoryInterface $students,
        SearchRepositoryInterface $searches,
        SubjectRepositoryInterface $subjects
    ) {
        parent::__construct();
        $this->database   = $database;
        $this->bookings = $bookings;
        $this->tutors = $tutors;
        $this->relationships = $relationships;
        $this->students = $students;
        $this->searches = $searches;
        $this->subjects = $subjects;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Set arguments
        $this->period = $this->argument('period');
        
        if ($offset = $this->argument('offset'))
        {
            $this->offset = $offset;
        } else {
            $this->offset = 0;
        }
        

        // Set Dates
        $this->end = $this->getEndDate();
        $this->start = $this->getStartDate();

        // Searches
        $data['searchCount'] = $this->getSearchCount();

        // Lessons
        $data['bookingsCount'] = $this->getBookingsCount();
        // $bookingsCountFirstLesson = $this->getBookingsCountFirstLesson();
        $data['bookingsValue'] = $this->getBookingsValue();
        $data['averageBookingsValue'] = $data['bookingsValue'] / $data['bookingsCount'];


        // New Students
        $data['newStudentsCount'] = $this->students->countBetweenDates($this->start, $this->end);

        $data['newStudentEnquiryRate'] = $data['searchCount'] !== 0 ? $data['newStudentsCount'] / $data['searchCount']: false;
        $data['newStudentOneMonthConversionRate'] = $this->newStudentFourWeekConversionRate();

        // Reactivated Students
        $data['fourWeekReactivatedStudentCount'] = $this->students->countReactivatedStudentsAfterPeriodBetweenDates('4 WEEK', $this->start, $this->end);
        $data['twelveWeekReactivatedStudentCount'] = $this->students->countReactivatedStudentsAfterPeriodBetweenDates('12 WEEK', $this->start, $this->end);
        

        // Tutors        
        $data['liveTutorsCount'] = $this->getLiveTutorsCount();
        $data['tutorApplications'] = $this->getTutorsBetweenDates();
        $data['oneMonthTutorLiveRate'] = $this->getFourWeekTutorLiveRate();

        // Relationships
        $data['newRelationshipsCount'] = $this->relationships->countBetweenDates($this->start, $this->end);
        $data['confirmedRelationships'] = $this->countFourWeekConfirmedRelationshipsBetweenDates();
        $data['confirmedByTutora'] = $this->getFourWeekRelationshipsConfirmedByTutora();

        // Searches
        $data['searchCount'] = $this->getSearchCount();
        // By Subject
        $data['mathsLessons'] = $this->getLessonsBySubject(1);
        $data['englishLessons'] = $this->getLessonsBySubject(22);
        $data['scienceLessons'] = $this->getLessonsBySubject(46);
        $data['languagesLessons'] = $this->getLessonsBySubject(82);
        $data['humanitiesLessons'] = $this->getLessonsBySubject(395);
        $data['businessLessons'] = $this->getLessonsBySubject(568);
        $data['computingLessons'] = $this->getLessonsBySubject(639);
        $data['musicLessons'] = $this->getLessonsBySubject(697);
        $data['admissionsLessons'] = $this->getLessonsBySubject(864);
        $data['sportsLessons'] = $this->getLessonsBySubject(920);

        if ($this->period = 'month') {
            $data['threeMonthCohortLessons'] = $this->runCohortAnalysisForPeriod('3 month');
            $data['sixMonthCohortLessons'] = $this->runCohortAnalysisForPeriod('6 month');
            $data['oneYearCohortLessons'] = $this->runCohortAnalysisForPeriod('1 year');
        }

        $start = $this->start;
        $end = $this->end;

        // Email
        \Mail::send('emails.admin.analytics', $data, function ($m) use ($start, $end) {
            $m->from('mark@tutora.co.uk', 'Your Application');

            $m->to('mark@tutora.co.uk')->subject("Analytics for period $this->start to $this->end");

        });
        \Mail::send('emails.admin.analytics', $data, function ($m) use ($start, $end) {
            $m->from('mark@tutora.co.uk', 'Your Application');

            $m->to('scott@tutora.co.uk')->subject("Analytics for period $this->start to $this->end");

        });



    }    

    public function getEndDate()
    {
        if ($this->period == 'month') {
            $end = new Carbon('first day of this Month');
            $end->startOfDay();
            $end->submonths($this->offset);
        }

        if ($this->period == 'week') {
            $end = new Carbon('Monday');
            $end->startOfDay();
            $end->subweeks($this->offset);
        }

        if ($this->period == 'day') {
            $end = new Carbon('today');
            $end->startOfDay();
            $end->subdays($this->offset);
        }

        return $end;
    
    }
   
    public function getStartDate()
    {
        $start = clone $this->end;

        if ($this->period == 'month') {
            $start->subMonths(1);
        }

        if ($this->period == 'week') {
            $start->subWeeks(1);
        }

        if ($this->period == 'day') {
            $start->subDays(1);
        }

        return $start;
    }

    public function getPriorPeriodStart($period)
    {
        $priorStart = clone $this->start;
        return $priorStart->modify($period);
    }

    public function getPriorPeriodEnd($period)
    {
        $priorEnd = clone $this->end;
        return $priorEnd->modify($period);
    }

    public function getSearchCount()
    {
        return $this->searches->countBetweenDates($this->start, $this->end);
    }

    public function getBookingsCount()
    {

        return $bookingsCount = $this->bookings->countByStatusBetweenDates(
            [LessonBooking::COMPLETED, LessonBooking::CONFIRMED], 
            null,
            $this->start,
            $this->end
        );
    }

    // public function getBookingsCountFirstLesson()
    // {
    //     return $this->bookings->countByStatusBetweenDatesWhereFirstLesson(
    //         [LessonBooking::COMPLETED, LessonBooking::CONFIRMED], 
    //         null,
    //         $this->start,
    //         $this->end
    //     );
    // }

    public function getBookingsValue()
    {
        return $bookingsValue = $this->bookings->valueByStatusBetweenDates(
            [LessonBooking::COMPLETED, LessonBooking::CONFIRMED], 
            null,
            $this->start,
            $this->end
        );
    }

    public function getLiveTutorsCount()
    {
        return $this->tutors->countLive();
    }

    public function getTutorsBetweenDates()
    {
        return $this->tutors->countBetweenDates($this->start, $this->end);
    }

    public function getFourWeekTutorLiveRate()
    {
        $priorPeriodStart = $this->getPriorPeriodStart('-4 weeks');
        $priorPeriodEnd = $this->getPriorPeriodEnd('-4 weeks');

        $applications = $this->tutors->countBetweenDates($priorPeriodStart, $priorPeriodEnd);
        $applicationsNowLive = $this->tutors->countLiveBetweenDates($priorPeriodStart, $priorPeriodEnd);

        if ($applications == 0) return false;

        return $applicationsNowLive / $applications;
    }

    public function newStudentFourWeekConversionRate()
    {
        $priorPeriodStart = $this->getPriorPeriodStart('-4 weeks');
        $priorPeriodEnd = $this->getPriorPeriodEnd('-4 weeks');

        $studentsCreatedBetweenDates = $this->students->countBetweenDates($priorPeriodStart, $priorPeriodEnd);

        $studentsWithConfirmedLessonsCreatedBetween = $this->students->countByConfirmedRelationshipsBetweenDates($priorPeriodStart, $priorPeriodEnd);

        if ($studentsCreatedBetweenDates == 0) return false;

        return $studentsWithConfirmedLessonsCreatedBetween / $studentsCreatedBetweenDates;
    }


    ////////////////////////////////////////////////////////////////////////////////////
    // SUBJECTS ////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////

    public function getLessonsBySubject($subjectId)
    {
        $subjects = $this->subjects->getDescendantsById($subjectId);

        return $this->bookings->countByStatusBySubjectsBetweenDates(
            [LessonBooking::COMPLETED, LessonBooking::CONFIRMED],
            null,
            $subjects,
            $this->start,
            $this->end

        );
    }

    ////////////////////////////////////////////////////////////////////////////////////
    // RELATIONSHIPS ///////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////


    public function getFourWeekRelationshipsConfirmedByTutora()
    {
        $priorPeriodStart = $this->getPriorPeriodStart('-4 weeks');
        $priorPeriodEnd = $this->getPriorPeriodEnd('-4 weeks');

        $relationships =  $this->relationships->getConfirmedBetweenDates($priorPeriodStart, $priorPeriodEnd);

        $count = 0;
        
        foreach ($relationships as $relationship)
        {
            $message = $relationship->message;

            if ($line = $message->lines()->first()) {
                if ($line->user_id == null)
                    $count++;
            }
        }

        return $count;
    }
    
    public function countFourWeekConfirmedRelationshipsBetweenDates() {
        $priorPeriodStart = $this->getPriorPeriodStart('-4 weeks');
        $priorPeriodEnd = $this->getPriorPeriodEnd('-4 weeks');

        return  $this->relationships->countConfirmedBetweenDates($priorPeriodStart, $priorPeriodEnd);
    }

    ////////////////////////////////////////////////////////////////////////////////////
    // COHORTS /////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////

    public function runCohortAnalysisForPeriod($period)
    {
        $priorPeriodStart = $this->getPriorPeriodStart('-' . $period);
        $priorPeriodEnd = $this->getPriorPeriodEnd('-' . $period);

        $completedBookings = $this->bookings->countByStatusWithinPeriodWhereStudentCreatedBetween(
            'completed',
            'paid',
            $period,
            $priorPeriodStart,
            $priorPeriodEnd
        );

        $convertedStudents = $this->students->countByConfirmedRelationshipsBetweenDates($priorPeriodStart, $priorPeriodEnd);

        if ($convertedStudents != 0)
        {
            return $completedBookings / $convertedStudents;
            
        }
        return null;

    }


}
