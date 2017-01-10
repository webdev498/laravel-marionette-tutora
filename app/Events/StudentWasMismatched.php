<?php namespace App\Events;

use App\Events\Event;
use App\Student;
use Illuminate\Queue\SerializesModels;

class StudentWasMismatched extends Event
{

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  Student $student
     * @return void
     */
    public function __construct(Student $student)
    {
        $this->student = $student;
    }

}
