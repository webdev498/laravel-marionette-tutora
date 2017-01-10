<?php

namespace App\Handlers\Events;

use App\Tutor;
use App\User;
use App\Events\MessageLineWasWritten;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\UserProfileRepositoryInterface;
use App\Messaging\ResponseTimeCalculator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;


class CalculateAverageResponseTime implements ShouldQueue
{
    

    protected $users;
    
    protected $profiles;
    
    protected $calc;

    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct(
        UserRepositoryInterface $users, 
        UserProfileRepositoryInterface $profiles, 
        ResponseTimeCalculator $calc
    ) {
        $this->users = $users;
        $this->profiles = $profiles;
        $this->calc = $calc;
    }
    

    /**
     * Handle the event.
     *
     * @param  MessageLineWasWritten  $event
     * @return void
     */
    public function handle(MessageLineWasWritten $event)
    {
        $line = $event->line;
        $message = $line->message;
        $relationship = $message->relationship;
        $tutor = $relationship->tutor;

        if ($this->users->findById($line->user_id) instanceof Tutor) {
            
            $responseTime = $this->calc->calculateResponseTime($tutor);

            $profile = $tutor->profile;

            $profile->response_time = $responseTime;

            $this->profiles->save($profile); 
        }
    }
}
