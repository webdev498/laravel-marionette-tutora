<?php return [
	
	//Number of times a tutor can not respond befor being taken offline
	'noResponseLimit' => 2,	

	//Maximum response time for use in response time calculation
	'maxResponseTime' => 4320,  // 3 days 

	// Reminder periods for offline tutors in days. Will send a reminder for each of the periods.
	'offlineReminderPeriods' => [
		30,
		60,
		120,
		240,
		360,
		720,
	],
];
