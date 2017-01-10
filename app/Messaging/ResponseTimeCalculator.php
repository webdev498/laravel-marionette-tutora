<?php

namespace App\Messaging;

use App\MessageLine;
use Carbon\Carbon;
use App\Repositories\EloquentMessageRepository;
use App\Tutor;

class ResponseTimeCalculator 
{
	protected $lines;
	protected $messages;

	public function __construct(MessageLine $lines, EloquentMessageRepository $messages)
	{

		$this->lines = $lines;
		$this->messages = $messages;
	}

	public function calculateResponseTime(Tutor $tutor)
	{
		$messages = $this->messages->getAllByUser($tutor);
		$responseTimes = [];
		foreach ($messages as $message)
		{
			if ($responseTimeForMessage = $this->responseTimeForMessage($message, $tutor)) {
				$responseTimes[] = $responseTimeForMessage;
			}
		}

		$averageResponseTime = $this->calculateAverage($responseTimes);

		return $averageResponseTime;
	}

	protected function responseTimeForMessage($message, $tutor)
	{
		$messageLines = $message->lines;

		$initialMessage = $messageLines->first();

		$firstResponse = $messageLines->first(function ($key, $value) use ($tutor) {
			return $value->user_id == $tutor->id;
		});

		if ($firstResponse != null) 
		{
			return $this->timeDifference($initialMessage, $firstResponse);
		}
		
		return null;

	}

	protected function timeDifference(MessageLine $initialMessage, MessageLine $firstResponse)
	{
		$timeOne = $initialMessage->updated_at;
		$timeTwo = $firstResponse->updated_at;
		
		return $timeOne->diffInSeconds($timeTwo)/60;

	}

	protected function calculateAverage(Array $times)
	{

		if (count($times) == 0) return null;

		$average = array_sum($times) / count($times);

		return $average;
	}
}