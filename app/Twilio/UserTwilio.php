<?php namespace App\Twilio;

use App\User;
use App\Tutor;
use App\UserReview;
use App\MessageLine;
use App\Messaging\Parser as MessageLineParser;

class UserTwilio extends AbstractTwilio
{

	public function sendMessageLineWasWrittenText(User $user, MessageLine $line)
	{

		$name = $user->first_name;
		$line = MessageLineParser::make($line, false);
		$url = route('message.redirect', [
        	'uuid' => $line->getLine()->message->uuid,
	    ]);

		if ($line->getLine()->user) {
        	$sender = $line->getLine()->user->first_name;
		} else {
			$sender = "Tutora";
		}

		$messagePreview = substr($line->getBody(), 0, 25);

		$message = "Hi {$name}, you have a new message from {$sender}: "
		."\"{$messagePreview}...\""
		."\n"
		."To reply click the link below"
		. "\n"
		. "{$url}";
		
		$this->sendToUser($user, $message);
	}

	public function sendApplicationMessageLineWasWritten(User $user, MessageLine $line)
	{

		$name = $user->first_name;
		$line = MessageLineParser::make($line, false);
		$url = route('message.redirect', [
        	'uuid' => $line->getLine()->message->uuid,
	    ]);

		if ($line->getLine()->user) {
        	$sender = $line->getLine()->user->first_name;
		} else {
			$sender = "Tutora";
		}

		$message = "Hi {$name}, {$sender} can help with your tuition request. "
		."\n"
		."Click the link below to read their message and reply."
		. "\n"
		. "{$url}";

		$this->sendToUser($user, $message);
	}
}