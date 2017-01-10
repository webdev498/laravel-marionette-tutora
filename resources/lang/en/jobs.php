<?php

return [

    'statuses' => [
        \App\Job::STATUS_PENDING => 'Pending',
        \App\Job::STATUS_LIVE    => 'Live',
        \App\Job::STATUS_EXPIRED => 'Expired',
        \App\Job::STATUS_CLOSED  => 'Closed',
        \App\Job::STATUS_NEW  => 'New',
        \App\Job::STATUS_RESERVED  => 'Reserved',
    ],

    'filters' => [
        \App\Search\JobSearcher::FILTER_SUBJECTS   => 'My Subjects',
        \App\Search\JobSearcher::FILTER_NONE       => 'All Subjects',
        \App\Search\JobSearcher::FILTER_FAVOURITES => 'Favourites',
    ],

    'message_student' => 'Message this Student',
    'view_message'    => 'View your Message',

    'create' => 'Create a Job',

    'message' => [
        'create' => [
            'placeholder'  => 'Write your reply, including when and where you can help. Explain why you are an expert at tutoring this subject. Do not include any contact details or your full name.',
            'submit'       => 'Apply for Job Opportunity',
        ],
        'confirmation' => [
            'title' => 'Ready to Send?',
            'introduction' => 'You will not be able to message this student again until they reply to you so make sure your message gives all the detail you wish to include.',
            'back' => 'Edit Message',
        ],
        'sent' => [
            'title' => 'Message Sent',
            'content' => 'Your message has been sent. Hopefully they will message you soon, but if you do not hear back, then the student has made other arrangements.',
            'summation' => 'Good luck!',
        ],
        'view' => [
            'title'  => 'Your Message',
        ],
    ],

    'emails' => [
        'apply_job' => 'Apply for this job',
    ],
];
