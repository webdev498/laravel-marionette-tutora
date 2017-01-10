<?php 

namespace App\Tasks;

use App\LessonBooking;
use App\Relationship;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;
use App\Task;
use App\Tutor;
use Carbon\Carbon;

trait TutorTasksTrait
{

	public $bookings;

	public $createFirstTasks = false;

	public $cancelled = false;

	public $confirmed = true;

	public function updateTasksForRelationshipOnLessonBooked(Relationship $relationship)
	{
		
		$this->createFirstTasks = false;
		$this->updateTasksForRelationship($relationship);
	}

	public function updateTasksForRelationshipOnLessonCancelled(Relationship $relationship, $confirmed = true)
	{
		$this->createFirstTasks = false;
		$this->cancelled = true;
		$this->confirmed = $confirmed;

		$this->updateTasksForRelationship($relationship);
	}

		public function updateTasksForRelationshipOnLessonCompleted(Relationship $relationship)
	{
		$this->createFirstTasks = true;
		$this->updateTasksForRelationship($relationship);
	}

	public function updateTasksForRelationship(Relationship $relationship)
	{
		// Lookups
		$this->bookings = app()->make(LessonBookingRepositoryInterface::class);
		$completedLessons = $this->bookings->getByRelationshipAndStatus(
            $relationship, LessonBooking::COMPLETED)
            ->count();
        $upcomingLessons = $this->bookings->getByRelationshipAndStatus(
            $relationship, [LessonBooking::PENDING, LessonBooking::CONFIRMED])
            ->count();
        $lessonCountByTutor = $this->bookings->countByTutorByStatus(
            $relationship->tutor, LessonBooking::COMPLETED);

       	// Case: Not creating first tasks

       	if ($this->createFirstTasks == false && $completedLessons != 0) {
       		$this->updateRebookTask($relationship->tutor, $relationship);
       	}

        // Case: First lesson for tutor
        if ($lessonCountByTutor == 1 && $completedLessons == 1) {
        	// Create First Lesson Task for tutor
        	$this->updateFirstLessonTask($relationship->tutor, $relationship);
        	
        }

        // Case: First lesson for relationship, not for tutor
        if ($lessonCountByTutor > 1 && $completedLessons == 1) {
        	
        	// Create First with student task for tutor
        	$this->updateFirstWithStudentTask($relationship->tutor, $relationship);
        }

        // Case: Not first lesson
        if ($lessonCountByTutor > 1 && $completedLessons > 1) {
        	
    		$this->updateRebookTask($relationship->tutor, $relationship);
        	
        }

        // Case: Cancelled first lesson
        if ($completedLessons == 0 && $this->cancelled == true && $this->confirmed == true) {
        	// Create cancelled task
        	$this->createCancelledTask($relationship);
        }
   
	}

	public function updateRebookTask(Tutor $tutor, Relationship $relationship)
	{
		$firstLessonTasks = $relationship->tasks()->whereIn('category', [Task::FIRST_WITH_STUDENT_REBOOK, Task::FIRST_WITH_STUDENT_NO_REBOOK])->get();

		$firstWithStudentTasks = $relationship->tasks()->whereIn('category',  [Task::FIRST_LESSON_REBOOK, Task::FIRST_LESSON_NO_REBOOK])->get();
		
		$rebookTasks = $relationship->tasks()->where('category', '=', Task::REBOOK)->get();

		$upcomingLessons = $this->bookings->getByRelationshipAndStatus(
            $relationship, [LessonBooking::PENDING, LessonBooking::CONFIRMED])
            ->count();


		// Check whether we need to update other tasks.
		if ($firstLessonTasks->count() != 0) {
			$this->updateFirstLessonTask($relationship->tutor, $relationship);
			return;
		}

		if ($firstWithStudentTasks->count() != 0) {
			$this->updateFirstWithStudentTask($relationship->tutor, $relationship);
			return;
		}

		// If we already have an existing rebook task - return
		
		if ($upcomingLessons == 0) { 
			if ($rebookTasks->count() != 0) {
				return;
			} else {
				$this->createRebookTask($relationship);
			}

		}

		if ($upcomingLessons > 0 && $rebookTasks->count() != 0) {
			$rebookTasks->first()->delete();	

		}


	}


	public function updateFirstLessonTask(Tutor $tutor, Relationship $relationship)
	{
		
		$upcomingLessons = $this->bookings->getByRelationshipAndStatus(
            $relationship, [LessonBooking::PENDING, LessonBooking::CONFIRMED])
            ->count();

        $existingTasks = $relationship->tasks()->whereIn('category', [Task::FIRST_LESSON_REBOOK, Task::FIRST_LESSON_NO_REBOOK])->get();


        if ($upcomingLessons == 0) {
      
        	if ($existingTasks->count() != 0) {
      			$task = $existingTasks->first();
      			$task->category = Task::FIRST_LESSON_REBOOK;
      			$task->save();
      		} else {
      			if ($this->createFirstTasks == true) {
	      			$this->createFirstLessonRebookTask($relationship);
	      		}
      		}
        } 

        if ($upcomingLessons != 0) {
			if ($existingTasks->count() != 0) {
      			$task = $existingTasks->first();
      			$task->category = Task::FIRST_LESSON_NO_REBOOK;
      			$task->save();
      		} else {
      			if ($this->createFirstTasks == true) {
	      			$this->createFirstLessonNoRebookTask($relationship);
	      		}
      		}
        }

	}

	public function updateFirstWithStudentTask(Tutor $tutor, Relationship $relationship)
	{
		$existingTasks = $relationship->tasks()->whereIn('category', [Task::FIRST_WITH_STUDENT_REBOOK, Task::FIRST_WITH_STUDENT_NO_REBOOK])->get();

		$upcomingLessons = $this->bookings->getByRelationshipAndStatus(
            $relationship, [LessonBooking::PENDING, LessonBooking::CONFIRMED])
            ->count();

		if ($upcomingLessons == 0) {
      
        	if ($existingTasks->count() != 0) {
      			$task = $existingTasks->first();
      			$task->category = Task::FIRST_WITH_STUDENT_REBOOK;
      			$task->save();
      		} else {
      			if ($this->createFirstTasks == true) {
	      			$this->createFirstWithStudentRebookTask($relationship);
	      		}
      		}
        } 

        if ($upcomingLessons != 0) {
			if ($existingTasks->count() != 0) {
      			$task = $existingTasks->first();
      			$task->category = Task::FIRST_WITH_STUDENT_NO_REBOOK;
      			$task->save();
      		} else {
      			if ($this->createFirstTasks == true) {
	      			$this->createFirstWithStudentNoRebookTask($relationship);
	      		}
      		}
        }

	}

	// Creating Tasks

	public function createFirstLessonNoRebookTask(Relationship $relationship)
	{
		
		
		$tutor = $relationship->tutor;
		$task = new Task();
		$task->category = Task::FIRST_LESSON_NO_REBOOK;
		$task->action_at = Carbon::now();
		$task->body = "FIRST LESSON: With " . $relationship->student->first_name;
		$relationship->tasks()->save($task);
		$tutor->tasks()->save($task);
	}

	public function createFirstLessonRebookTask(Relationship $relationship)
	{
		$tutor = $relationship->tutor;
		$task = new Task();
		$task->category = Task::FIRST_LESSON_REBOOK;
		$task->action_at = Carbon::now();
		$task->body = "FIRST LESSON: With " . $relationship->student->first_name;
		$relationship->tasks()->save($task);
		$tutor->tasks()->save($task);
	}

	public function createFirstWithStudentNoRebookTask(Relationship $relationship)
	{
		$tutor = $relationship->tutor;
		$task = new Task();
		$task->category = Task::FIRST_WITH_STUDENT_NO_REBOOK;
		$task->action_at = Carbon::now();
		$task->body = "FIRST WITH STUDENT: With " . $relationship->student->first_name;
		$relationship->tasks()->save($task);
		$tutor->tasks()->save($task);
	}

	public function createFirstWithStudentRebookTask(Relationship $relationship)
	{
		$tutor = $relationship->tutor;
		$task = new Task();
		$task->category = Task::FIRST_WITH_STUDENT_REBOOK;
		$task->action_at = Carbon::now();
		$task->body = "FIRST WITH STUDENT: With " . $relationship->student->first_name;
		$relationship->tasks()->save($task);
		$tutor->tasks()->save($task);
	}

	public function createRebookTask(Relationship $relationship)
	{
		$tutor = $relationship->tutor;
		$task = new Task();
		$task->category = Task::REBOOK;
		$task->action_at = Carbon::now();
		$task->body = "REBOOK: With " . $relationship->student->first_name;
		$relationship->tasks()->save($task);
		$tutor->tasks()->save($task);
	}

	public function createCancelledTask(Relationship $relationship)
	{
		if ($relationship->tasks()->where('category', '=', Task::CANCELLED_FIRST_LESSON)->count() == 0 ) {
			loginfo('called createCancelledTask');
			$tutor = $relationship->tutor;
			$task = new Task();
			$task->category = Task::CANCELLED_FIRST_LESSON;
			$task->action_at = Carbon::now();
			$task->body = "CANCELLED FIRST LESSON: With " . $relationship->student->first_name;
			$relationship->tasks()->save($task);
			$tutor->tasks()->save($task);
			loginfo('finished createCancelledTask');
			
		}

	}



}

