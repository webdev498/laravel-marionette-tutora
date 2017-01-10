<?php namespace App\Events;

use App\MessageLine;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class StudentMessageLineWasWritten extends Event
{

    use SerializesModels;

    /**
     * @var MessageLine
     */
    public $line;

    /**
     * Create a new event instance.
     *
     * @param MessageLine $line
     */
    public function __construct(MessageLine $line)
    {
        $this->line = $line;
    }

}
