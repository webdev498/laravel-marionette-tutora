<?php namespace App\Console\Commands\Reports;

use App\Console\Commands\Command;
use Illuminate\Database\DatabaseManager;
use DB;
use Carbon\Carbon;
use App\Student;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Contracts\StudentStatusRepositoryInterface;

class RunStudentStatusReport extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tutora:run_student_status_report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update All Student Statuses';

    /**
     * @var Database
     */
    protected $database;
   
   /**
     * @var StudentRepositoryInterface
     */
    protected $students;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        DatabaseManager          $database,
        StudentStatusRepositoryInterface $students
    ) {
        parent::__construct();
        $this->students = $students;
        $this->database   = $database;
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $chattingStudents = $this->students->getChattingStudents();
        foreach ($chattingStudents as $student)
        {
            $student->status = 'chatting';            
            $student->save();
        }

        $mismatchedStudents = $this->students->getMismatchedStudents();
        foreach ($mismatchedStudents as $student)
        {
            $student->status = 'mismatched';            
            $student->save();
        }

        $pendingStudents = $this->students->getPendingStudents();
        foreach ($pendingStudents as $student)
        {
            $student->status = 'pending';            
            $student->save();
        }

        $confirmedStudents = $this->students->getConfirmedStudents();
        foreach ($confirmedStudents as $student)
        {
            $student->status = 'confirmed';            
            $student->save();
        }

        $recurringStudents = $this->students->getRecurringStudents();
        foreach ($recurringStudents as $student)
        {
            $student->status = 'recurring';            
            $student->save();
        }

        $noMessageStudents = $this->students->getNoMessageStudents();
        foreach ($noMessageStudents as $student)
        {
            $student->status = 'no_message';            
            $student->save();
        }

        $firstLessonStudents = $this->students->getFirstLessonStudents();
        foreach ($firstLessonStudents as $student)
        {   
            $student->status = 'first'; 
            $student->save();
        }

        $rebookPlusStudents = $this->students->getRebookPlusStudents();
        foreach ($rebookPlusStudents as $student)
        {   
            $student->status = 'rebookPlus';            
            $student->save();
        }

        $rebookStudents = $this->students->getRebookStudents();
        foreach ($rebookStudents as $student)
        {   
            $student->status = 'rebook';            
            $student->save();
        }

        $studentNotRepliedStudents = $this->students->getStudentNotRepliedStudents();
        foreach ($studentNotRepliedStudents as $student)
        {   
            $student->status = 'student_not_replied';            
            $student->save();
        }

        $students = $this->students->getFailedStudents();
        foreach ($students as $student)
        {
            $student->status = 'failed';            
            $student->save();
        }


    }    

}
