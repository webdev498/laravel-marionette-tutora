<?php

return [

    'expire_period' => 14, // Days

    'send_at_hour' => 18, // 6pm

    'jobs_newer_than_period' => 3,   // Send jobs newer than 3 days ago via email.

    'recent_period' => 14,    // Used to figure out period in which jobs should be classed as "recent"

    'new_period' => 16 // hours for which a job is at new before it goes to "pending"
];
