<?php

return [

    'cancel_period' => 720,                   // 12 hours

    'unauthed_payment_cancel_period' => 720,  // 12 hours

    'authorise_payment_period' => [
        'from' => 720,                        // 12 hours
        'to'   => 1440,                       // 1 day
    ],

    'max_retry_payment_attempts' => 3,  // After this, system will not retry payments 

    'retry_payment_periods' => [
            1 => 1440, // 1 day after the lesson
            2 => 2880, // 2 days after the lesson
    ],

    'capture_payment_period' => -1440,        // 24 hours

    'reminder_upcoming_period' => -1440,      // 24 hours
    
    'rebook_notification_period' => 4320,      // 3 days

    'first_reminder_still_pending_period' => -2880, // 48 hours

    'second_reminder_still_pending_period' => -1440, //24 hours

    'reminder_review_period' => 1440,         // 24 hours
];
