<?php namespace App\Events;

use App\Tutor;
use Illuminate\Queue\SerializesModels;

class TutorSubjectsWereEdited extends Event
{

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Tutor $tutor)
    {
        $this->tutor = $tutor;
    }

}
