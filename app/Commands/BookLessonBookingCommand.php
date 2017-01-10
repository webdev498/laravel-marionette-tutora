<?php namespace App\Commands;

use App\User;
use App\Commands\Command;

class BookLessonBookingCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($confirm_lesson, $id, User $user)
    {
        $this->confirm_lesson = $confirm_lesson;
        $this->id             = $id;
        $this->user           = $user;
    }

}
