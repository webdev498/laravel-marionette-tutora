<?php
return [
	'distanceInfectionPoint' => 3.5,

	'minProfileScore' => 0.4,
	'minBookingScore' => 0.25,

	'rawScoreAdjustmentFactor' => 0.7 ,

	'ageDecayRate' => 0.25,        // Higher = faster. 0.25 means 7th relationship is worth 1/2 as much

	'quality' => [
		'averageQuality'=> 7,		// What is the average quality rating? 
		'exponent' 		=> 1.5,		// How much quicker should higher scores be?
		'offset'        => 20		// How much should low scores be punished? Lower = more.

	],
	'responseTime' => [
		'minWeight' => 0.6,
		'averageResponseTime' => '2880',	// in minutes, set at 2 days
		'offset'	=> 1.5

	],
	'transgressions' => [
		'maxScore'	=> 1.1,
		'exponent' 	=> 0.3,
	],
	'lessons' => [

		'maxPowerFactor' 		=> 3,		// Maximum power factor to apply to multiple confirmed relationships
		'exponent' 				=> 0.6,	 	// How quickly that maximum power is reached. 

		'upcomingLessonsWeight' => 0.6,

		'twoWeekMaxScore' => 2,
		'twoWeekLessonScores' => [
			0 => 0.2,
			1 => 0.7,
			2 => 1.3,
			3 => 1.5,
			4 => 1.7,
			5 => 2.0,
			6 => 2.3,
		],

		'fourWeekMaxScore' => 2.5,
		'fourWeekLessonScores' => [
			0 => 0.2,
			1 => 0.4,
			2 => 0.7,
			3 => 1.2,
			4 => 1.4,
			5 => 1.6,
			6 => 1.8,
			7 => 1.9
		],

		'eightWeekMaxScore' => 3,
		'eightWeekLessonScores' => [
			0 => 0.15,
			1 => 0.3,
			2 => 0.65,
			3 => 0.9,
			4 => 1.2,
			5 => 1.6,
			6 => 1.8,
			7 => 1.9,
			8 => 2.1,
			9 => 2.3,
			10 => 2.4,
			11 => 2.5,
			12 => 2.6,
			13 => 2.8
		],
		'twelveWeekMaxScore' 	=> 4,	
		'twelveWeekLessonScores' => [
			0 => 0.15,
			1 => 0.3,
			2 => 0.65,
			3 => 0.9,
			4 => 1,
			5 => 1.1,
			6 => 1.2,
			7 => 1.3,
			8 => 1.5,
			9 => 1.7,
			10 => 1.9,
			11 => 2.2,
			12 => 2.4,
			13 => 2.6,
			14 => 2.8,
			15 => 3.0,
			16 => 3.1,
			17 => 3.2,
			18 => 3.3,
			19 => 3.4,
			20 => 3.6,
			21 => 3.7,
			22 => 3.8,
			23 => 3.9,
			24 => 4.0,
		],

		'twentyFourWeekMaxScore' 	=> 5.4,	
		'twentyFourWeekLessonScores' => [
			0 => 0.15,
			1 => 0.3,
			2 => 0.65,
			3 => 0.9,
			4 => 1,
			5 => 1.1,
			6 => 1.2,
			7 => 1.3,
			8 => 1.4,
			9 => 1.6,
			10 => 1.8,
			11 => 2.0,
			12 => 2.2,
			13 => 2.3,
			14 => 2.5,
			15 => 2.6,
			16 => 2.8,
			17 => 3.0,
			18 => 3.2,
			19 => 3.3,
			20 => 3.4,
			21 => 3.5,
			22 => 3.6,
			23 => 3.7,
			24 => 3.8,
			25 => 4.0,
			26 => 4.2,
			27 => 4.4,
			28 => 4.6,
			29 => 4.8,
			30 => 5.0,
			31 => 5.2,
			32 => 5.4
		],
						
		
	],

	'enquiryToBookingTable' => [
		0 => [
			0 => 1,
		],

		1 => [
			0 => 0.95,
			1 => 1.5
		],
		2 => [
			0 => 0.85,
			1 => 1.3,
			2 => 1.9
		],
		3 => [
			0 => 0.8,
			1 => 1.3,
			2 => 1.75,
			3 => 2.3,
		],
		4 => [
			0 => 0.75,
			1 => 1.15,
			2 => 1.6,
			3 => 2,
			4 => 2.5,
		],
		5 => [
			0 => 0.7,
			1 => 1.1,
			2 => 1.5 , 
			3 => 1.8,
			4 => 2.3,
			5 => 2.9,
		],

		6 => [
			0 => 0.65,
			1 => 1.05,
			2 => 1.4,
			3 => 1.6,
			4 => 2.1,
			5 => 2.7,
			6 => 3.5,
		],

		7 => [
			0 => 0.6,
			1 => 1,
			2 => 1.3,
			3 => 1.5,
			4 => 2,
			5 => 2.6,
			6 => 3.3,
			7 => 4
		],

		8 => [
			0 => 0.55,
			1 => 1,
			2 => 1.4,
			3 => 1.7,
			4 => 2,
			5 => 2.5,
			6 => 3.1,

		],

		9 => [
			0 => 0.5,
			1 => 0.95,
			2 => 1.35,
			3 => 1.55,
			4 => 1.8,
			5 => 2.4,
			6 => 3,
			7 => 3.7
		],
		10 => [
			0 => 0.45,
			1 => 0.9,
			2 => 1.25,
			3 => 1.45,
			4 => 1.7,
			5 => 2.3,
			6 => 2.9,
			7 => 3.5
		],
		11 => [
			0 => 0.4,
			1 => 0.8,
			2 => 1.15,
			3 => 1.45,
			4 => 1.6,
			5 => 2.2,
			6 => 2.5,
			7 => 3.0
		],
		12 => [
			0 => 0.35,
			1 => 0.75,
			2 => 0.85,
			3 => 1.15,
			4 => 1.5,
			5 => 2.1,
			6 => 2.7,
			7 => 3.0,
			8 => 3.5
		],
		13 => [
			0 => 0.3,
			1 => 0.7,
			2 => 1.2,
			3 => 1.4,
			4 => 1.7,
			5 => 2.0,
			6 => 2.5,
			7 => 2.9,
			8 => 3.4,
		],
		14 => [
			0 => 0.25,
			1 => 0.65,
			2 => 1.1,
			3 => 1.25,
			4 => 1.5,
			5 => 1.8,
			6 => 2.4,
			7 => 2.8,
			8 => 3.2,
			9 => 3.5
		],
		15 => [
			0 => 0.22,
			1 => 0.6,
			2 => 1,
			3 => 1.1,
			4 => 1.3,
			5 => 1.7,
			6 => 2.1,
			7 => 2.6,
			8 => 3.0,
			9 => 3.3
		],
		16 => [
			0 => 0.22,
			1 => 0.53,
			2 => 0.95,
			3 => 1.1,
			4 => 1.3,
			5 => 1.7,
			6 => 2.1,
			7 => 2.6,
			8 => 3.0,
			9 => 3.3
		],
		17 => [
			0 => 0.16,
			1 => 0.5,
			2 => 0.9,
			3 => 1.05,
			4 => 1.2,
			5 => 1.6,
			6 => 2.0,
			7 => 2.5,
			8 => 2.9,
			9 => 3.2,
			10 => 3.9
		],
		18 => [
			0 => 0.13,
			1 => 0.45,
			2 => 0.85,
			3 => 1.1,
			4 => 1.15,
			5 => 1.55,
			6 => 1.9,
			7 => 2.4,
			8 => 2.8,
			9 => 3.0,
			10 => 3.5,
			11 => 3.9,
		],
		19 => [
			0 => 0.10,
			1 => 0.35,
			2 => 0.8,
			3 => 1,
			4 => 1.1,
			5 => 1.4,
			6 => 1.8,
			7 => 2.2,
			8 => 2.6,
			9 => 2.9,
			10 => 3.3,
			11 => 3.6,
			12 => 4.0
		],
		20 => [
			0 => 0.09,
			1 => 0.30,
			2 => 0.60,
			3 => 0.9,
			4 => 1.05,
			5 => 1.35,
			6 => 1.7,
			7 => 2.1,
			8 => 2.5,
			9 => 2.8,
			10 => 3.2,
			11 => 3.4,
			12 => 3.8,
		],
		21 => [
			0 => 0.08,
			1 => 0.28,
			2 => 0.57,
			3 => 0.85,
			4 => 1.05,
			5 => 1.35,
			6 => 1.7,
			7 => 2.1,
			8 => 2.5,
			9 => 2.8,
			10 => 3.2,
			11 => 3.4,
			12 => 3.8,
		],
		22 => [
			0 => 0.07,
			1 => 0.26,
			2 => 0.55,
			3 => 0.83,
			4 => 1.00,
			5 => 1.3,
			6 => 1.6,
			7 => 2.0,
			8 => 2.4,
			9 => 2.6,
			10 => 3.0,
			11 => 3.2,
			12 => 3.5,
			13 => 3.9,
		],
		23 => [
			0 => 0.07,
			1 => 0.26,
			2 => 0.55,
			3 => 0.83,
			4 => 1.00,
			5 => 1.3,
			6 => 1.6,
			7 => 2.0,
			8 => 2.4,
			9 => 2.6,
			10 => 3.0,
			11 => 3.2,
			12 => 3.5,
			13 => 3.9,
		],

		23 => [
			0 => 0.06,
			1 => 0.24,
			2 => 0.52,
			3 => 0.80,
			4 => 0.95,
			5 => 1.25,
			6 => 1.5,
			7 => 1.9,
			8 => 2.3,
			9 => 2.5,
			10 => 2.8,
			11 => 3.0,
			12 => 3.3,
			13 => 3.6,
		],
		24 => [
			0 => 0.05,
			1 => 0.22,
			2 => 0.50,
			3 => 0.75,
			4 => 0.9,
			5 => 1.2,
			6 => 1.4,
			7 => 1.8,
			8 => 2.2,
			9 => 2.4,
			10 => 2.6,
			11 => 2.9,
			12 => 3.2,
			13 => 3.5,
			14 => 3.7,
		],
		25 => [
			0 => 0.04,
			1 => 0.20,
			2 => 0.45,
			3 => 0.73,
			4 => 0.87,
			5 => 1.15,
			6 => 1.25,
			7 => 1.6,
			8 => 1.8,
			9 => 2.1,
			10 => 2.4,
			11 => 2.7,
			12 => 3.1,
			13 => 3.4,
			14 => 3.6,
		],

	],



];