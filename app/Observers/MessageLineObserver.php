<?php 

namespace App\Observers;

use App\MessageLine;
use App\Messaging\Flagger;
use App\Relationship;
use App\Student;
use App\Tutor;
use App\User;

class MessageLineObserver
{
	public function created(MessageLine $line)
	{
		$message      = $line->message;
        $relationship = $message->relationship;
        $user  		  = $line->user;

        if ($user instanceof User && $relationship instanceof Relationship) {
	        $this->flagMessageLine($line, $user, $relationship);
	    }


        if ($user instanceof Tutor) {
	        $this->updateRelationshipOnTutorMessageLineWritten($relationship);
        }

        if ($user instanceof Student) {
	        $this->updateRelationshipOnStudentMessageLineWritten($relationship);
        }       
	}

	/**
	* Update Relationship Status when Student sends message
	* @param Relationship $relationship
	* @return void
	*/
	protected function updateRelationshipOnStudentMessageLineWritten(Relationship $relationship) 
	{
		if ($relationship->status == Relationship::NO_REPLY_STUDENT) {
	            $relationship->status = Relationship::CHATTING;
	            $relationship->save();
	        }
    }

	/**
	* Update Relationship Status when Tutor sends message
	* @param Relationship $relationship
	* @return void
	*/
	protected function updateRelationshipOnTutorMessageLineWritten(Relationship $relationship) 
	{
		if ($relationship->status == Relationship::NO_REPLY) {
	            $relationship->status = Relationship::CHATTING;
	            $relationship->save();
	        }
	    }

	/**
	* Flag message if it contains worrying phrasse
	* @param Messageline $line
	* @param User $user
	* @param Relationship $relationship
	* @return void
	*/
    protected function flagMessageLine(Messageline $line, User $user, Relationship $relationship)
    {
	    $flagger = app(Flagger::class, [$line, $relationship]);

	    if ($flagger->flagged()) {
		    $line->flagged = true;
		    $line->save();
	    }
	    

    }

}