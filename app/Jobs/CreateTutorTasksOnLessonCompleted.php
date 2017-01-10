<?php

namespace App\Jobs;

use App\LessonBooking;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;
use App\Task;
use App\Tasks\TutorTasksTrait;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateTutorTasksOnLessonCompleted implements SelfHandling, ShouldQueue
{
    protected $booking;

    use InteractsWithQueue, SerializesModels, Queueable, TutorTasksTrait;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(LessonBooking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Execute the job.
     * 
     * @param LessonBookingRepositoryInterface $bookings
     * @return void
     */
    public function handle(LessonBookingRepositoryInterface $bookings)
    {
        // Lookups
        $booking = $this->booking;
        $lesson = $booking->lesson;
        $relationship = $lesson->relationship;
        $tutor = $relationship->tutor;


        $this->updateTasksForRelationshipOnLessonCompleted($relationship);

        $lessonCountByTutor = $bookings->countByTutorByStatus(
            $tutor, LessonBooking::COMPLETED);

         

        if ($lessonCountByTutor == 20) {
            // First lesson task
            $task = new Task();
            
            $task->body      = 'PRICE INCREASE: 20 lessons reached';
            $task->action_at = Carbon::now()->addDays(1);
            $task->category  = Task::LESSON_COUNT;
            // Save
            $tutor->tasks()->save($task);
        }    

        if ($lessonCountByTutor == 100) {
            // First lesson task
            $task = new Task();
            
            $task->body      = 'PRICE INCREASE: 100 lessons reached';
            $task->action_at = Carbon::now()->addDays(1);
            $task->category  = Task::LESSON_COUNT;
            // Save
            $tutor->tasks()->save($task);
        }  
    
    }
}
