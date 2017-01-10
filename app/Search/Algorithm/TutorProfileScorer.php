<?php 
namespace App\Search\Algorithm;

use App\Repositories\Contracts\LessonBookingRepositoryInterface;
use App\Tutor;
use App\Relationship;
use App\UserProfile;
use App\LessonBooking;
use Carbon\Carbon;


class TutorProfileScorer 
{
	protected $profile;

	protected $tutor;

	protected $bookings;

	protected $relationships;

	protected $confirmedRelationships;

	public function __construct(LessonBookingRepositoryInterface $bookings)
	{
		$this->weights = config('algorithm');
		$this->bookings = $bookings;
	}

	public function setTutor(Tutor $tutor)
	{
		$this->tutor = $tutor;
		$this->profile = $tutor->profile;
		$this->relationships = $this->tutor->relationships()->where('created_at', '<', Carbon::now()->subWeeks(2))->where('is_application', '=', false)->count();
		$this->confirmedRelationships = $this->tutor->relationships()->where('created_at', '<', Carbon::now()->subWeeks(2))->where('is_application', '=', false)->where('is_confirmed', '=', true)->count();
	}
	
	public function calculateProfileScore()
	{

		$qualityScore = $this->qualityScore();
		$responseTimeScore = $this->responseTimeScore();
		// $transgressionScore = $this->transgressionScore();
		$conversionScore = $this->conversionScore();
		$lessonScore = $this->lessonScore();
		$rawProfileScore = $qualityScore * $responseTimeScore * $conversionScore * $lessonScore;

		$profileScore = $this->adjustRawProfileScore($rawProfileScore);

		return $profileScore;

	}

	protected function adjustRawProfileScore($rawProfileScore)
	{
		if ($rawProfileScore > 1) {
			$profileScore = ( ($rawProfileScore - 1) * $this->weights['rawScoreAdjustmentFactor'] ) + 1;
		}

		if ($rawProfileScore <= 1) {
			$profileScore = $rawProfileScore;
		}

		if ($profileScore > 25) {
			$profileScore = 25;
		}

		return $profileScore;
	}

	public function calculateBookingScore()
	{
		$bookingScore = $this->lessonScore();

		return $bookingScore;
	}

	protected function qualityScore()
	{
		// Vars
		$quality = $this->profile->quality;
		$averageQuality = $this->weights['quality']['averageQuality'];
		$exponent = $this->weights['quality']['exponent'];
		$offset = $this->weights['quality']['offset'];

		// Calculation
		$unweightedQualityScore = pow(($quality+$offset), $exponent);
		$weighting = pow(($averageQuality+$offset), $exponent);

		$rawQualityScore = $unweightedQualityScore / $weighting;

		// Adjusting if we have other data
		$relationships = $this->relationships;

		$factor = 1 / ( (0.2 *  $relationships) + 1);

		$qualityScore = (($rawQualityScore - 1) * $factor ) + 1;

		return $qualityScore;
	}

	protected function responseTimeScore()
	{
		// Variables
		$averageResponseTime = $this->weights['responseTime']['averageResponseTime'];
		$minWeight = $this->weights['responseTime']['minWeight'];
		$offset = $this->weights['responseTime']['offset'];
		$responseTime = $this->profile->response_time ? $this->profile->response_time : $averageResponseTime;

		// Calculation
		$unweightedResponseTimeScore = 1 / (($responseTime / $averageResponseTime) + $offset);  
		$averageResponseTimeScore = 1 / (1 + $offset);
		$responseTimeScore = $unweightedResponseTimeScore / $averageResponseTimeScore;

		// Return
		if ($responseTimeScore < $minWeight) {
			return $responseTimeScore = $minWeight;
		}

		return $responseTimeScore;
	}

	protected function conversionScore()
	{
		$relationships = $this->relationships;

		$confirmedRelationships = $this->confirmedRelationships;

		if (array_key_exists($relationships, $this->weights['enquiryToBookingTable']))	{
			dump('first array key exists');
			if (array_key_exists($confirmedRelationships, $this->weights['enquiryToBookingTable'][$relationships])) {
					dump('array keys exist');
					return $score = $this->weights['enquiryToBookingTable'][$relationships][$confirmedRelationships];
			}
		}

		// Default if not in array

		$score = pow(1.2, ($confirmedRelationships / ($relationships * 0.2)));
		return $score;

	}

	protected function lessonScore()
	{
		
		// We are going to divide the confirmed relationships into age groups. 
		// Then foreach relationship, score the number of lessons they have had. 
		// We then calculate their average score, and weight it on the number of confirmed relationships.

		$confirmedRelationships = $this->getConfirmedRelationshipsByMinimumAge(14);

		$scores = [];
		$count = 0;

		foreach ($confirmedRelationships as $relationship)
		{
			$scores[] = $this->scoreConfirmedRelationship($relationship);
			$count ++;
		}

		$score = $this->calculateOverallLessonScore($scores);

		$powerFactor = $this->calculateLessonPowerFactor($count);
		$score = pow($score, $powerFactor);
		return $score;

	}


	protected function scoreConfirmedRelationship(Relationship $relationship)
	{
		
		// Variables
		$twoWeekLessonScores = config('algorithm.lessons.twoWeekLessonScores');
		$fourWeekLessonScores = config('algorithm.lessons.fourWeekLessonScores');
		$eightWeekLessonScores = config('algorithm.lessons.eightWeekLessonScores');
		$twelveWeekLessonScores = config('algorithm.lessons.twelveWeekLessonScores');
		$twentyFourWeekLessonScores = config('algorithm.lessons.twentyFourWeekLessonScores');

		// Calculate Age of relationship (by first lesson date - whether confirmed or not)
		$age = $this->calculateRelationshipAge($relationship);
		$completedBookings = $relationship->bookings->where('status', LessonBooking::COMPLETED)->count();
		$upcomingBookings = $relationship->bookings->where('status', LessonBooking::CONFIRMED)->count();
		if ($upcomingBookings > 5) 
			$upcomingBookings = 5;

		$totalBookings = intval($completedBookings + ($upcomingBookings * config('algorithm.lessons.upcomingLessonsWeight')));

		if ($age < 14) {
			$score = 1;
		}

		if ($age >= 14 && $age < 28)
		{

			if (array_key_exists($totalBookings, $twoWeekLessonScores)) {
				$score = $twoWeekLessonScores[$totalBookings];
			} else
			{
				$score = config('algorithm.lessons.twoWeekMaxScore');
			}
		}


		if ($age >= 28 && $age < 56)
		{
			if (array_key_exists($totalBookings, $fourWeekLessonScores)) {
				$score = $fourWeekLessonScores[$totalBookings];
			} else
			{
				$score = config('algorithm.lessons.fourWeekMaxScore');
			}
		}

		if ($age >= 56 && $age < 84)
		{
			if (array_key_exists($totalBookings, $eightWeekLessonScores)) {
				$score = $eightWeekLessonScores[$totalBookings];
			} else
			{
				$score = config('algorithm.lessons.eightWeekMaxScore');
			}
		}


		if ($age >= 84 && $age < 168)
		{
			
			if (array_key_exists($totalBookings, $twelveWeekLessonScores)) {
				$score = $twelveWeekLessonScores[$totalBookings];
			} else
			{
				$score = config('algorithm.lessons.twelveWeekMaxScore');
			}
		}

		if ($age >= 168)
		{
			if (array_key_exists($totalBookings, $twentyFourWeekLessonScores)) {
				$score = $twentyFourWeekLessonScores[$totalBookings];
			} else
			{
				$score = config('algorithm.lessons.twentyFourWeekMaxScore');
			}
		}
		return $score;
	}

	protected function getConfirmedRelationshipsByMinimumAge($minAge)
	{
		$relationships = $this->tutor->relationships()->with('lessons.bookings')
			->where('is_confirmed', 1)
			->get();


		$relationships = $relationships->filter(function ($relationship) use ($minAge) {
		    $firstLesson = $relationship->lessons->first()->bookings->first();
		    $age = Carbon::now()->diffInDays($firstLesson->start_at);
		    return $age > $minAge;
		});

		return $relationships;
	}

	protected function calculateRelationshipAge(Relationship $relationship)
	{
		$firstLesson = $relationship->lessons->first()->bookings->first();

		$start = $firstLesson->start_at;

		$age = Carbon::now()->diffInDays($start);

		return $age;
	}


	protected function calculateLessonPowerFactor($count)
	{

		$divisor = (config('algorithm.lessons.exponent') * $count) - config('algorithm.lessons.exponent') - (1 / (1 - config('algorithm.lessons.maxPowerFactor')));

		return config('algorithm.lessons.maxPowerFactor') - ( 1 / $divisor);
	}

	protected function calculateOverallLessonScore(Array $scores)
	{
		$scores = array_reverse($scores);

		$decay = config('algorithm.ageDecayRate');

		$weightedScores = [];


		foreach ($scores as $number => $score) 
		{
			$weight = 2 / (($decay * $number) + 2);

			$weightedScores[$number]['weight'] = $weight;
			$weightedScores[$number]['score'] = $score * $weight;
		}

		$weightedScores = collect($weightedScores);

		if (count($weightedScores) > 0) {
			$score = $weightedScores->sum('score') / $weightedScores->sum('weight');
		} else {
			$score = 1;
		}

		return $score;
	}


}