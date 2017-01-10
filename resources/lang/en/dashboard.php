<?php

return [

    'tutor' => [

        'dashboard' => [
            'heading' => 'Dashboard',
            'introduction' => 'Your dashboard provides an overview of your upcoming
                lessons and any messages you have received.',
        ],

        'messages' => [
            'heading'      => 'Messages',
            'introduction' => 'The Messages tab gives an overview of your communication
                with students. When you have a new enquiry, this is where it will
                appear. To reply, simply click on the message and start typing.',
            'none'         => 'You haven\'t received any messages, yet.',
        ],

        'students' => [
            'heading'      => 'Students',
            'introduction' => 'The Students tab is where you can manage your students
                and see upcoming lessons with them.',
            'none'         => 'You aren\'t connected with any students, yet.',
        ],

        'lessons' => [
            'heading'      => 'Lessons',
            'introduction' => 'The lessons tab gives an overview of your upcoming and
                completed lessons. Lessons will appear as pending until your student
                confirms the session by entering their payment details.',
            'none'         => 'You haven\'t booked any lessons, yet',
        ],

        'jobs' => [
            'heading'            => 'Job Opportunities',
            'introduction-title' => 'Do you have space to take on more students?',
            'introduction'       => 'If so, message any of the students below to offer your services.
                Let them know how, when and where you can help them.',
            'none'               => 'You haven\'t booked any lessons, yet',
        ],

        'account' => [
            'heading'      => 'Account',
            'introduction' => 'This is where you can manage your personal information.
                It’s also how you tell us how to pay you.',

            'personal' => [
                'introduction' => 'This is where you manage your personal information,
                    like your name and contact details.',
            ],

            'payment' => [
                'introduction' => 'This is where you tell us how to pay you!',
            ],

            'identification' => [
                'introduction' => 'This is where you upload your identification
                    documents to prove who you are. We have to comply with the
                    money laundering act.',

                'verification' => [
                    'not-attempted' => [
                        'heading' => 'Important information',
                        'details' => 'Please ensure this information is correct. Once your
                            identification has been verified, these details <strong>cannot
                            be changed</strong>.',
                    ],

                    'pending' => [
                        'heading' => 'Checking identification...',
                        'details' => "This usually takes a few minutes. Feel free to leave this page,
                            we'll let you know when it's done.",
                    ],

                    'unverified' => [
                        'heading' => 'Identification check failed.',
                        'details' => "Don't panic! It's probably nothing.</p>
                            <p>Please check the details you've entered, and that the scan of your
                            identification is clear and complete. A scan of a passport or driving
                            licence works best."
                    ],

                    'verified' => [
                        'heading' => 'Identification verification passed.',
                        'details' => "Your identification has been verified.",
                    ],
                ]
            ],
        ],

    ],

    'student' => [

        'dashboard' => [
            'heading' => 'Dashboard',
            'introduction' => 'Your dashboard provides an overview of your upcoming
                lessons and any messages you have received.',
        ],

        'messages' => [
            'heading'      => 'Messages',
            'introduction' => 'The Messages tab gives an overview of your communication
                with your tutors. To reply, simply click on the message and start typing.',
            'none'         => 'You haven\'t received any messages, yet.',
        ],

        'tutors' => [
            'heading'      => 'Tutors',
            'introduction' => 'The tutors tab is where you can manage your tutors and
                see completed lessons with them.',
            'none'         => 'You aren\'t connected with any tutors, yet.',
        ],

        'lessons' => [
            'heading' => 'Lessons',
            'introduction' => 'The lessons tab gives an overview of your upcoming and
                completed lessons.',
        ],

        'account' => [
            'heading'      => 'Account',
            'introduction' => 'This is where you can manage your personal and payment information.',

            'personal' => [
                'introduction' => 'This is where you manage your personal information,
                    like your name and contact details.',
            ],

            'payment' => [
                'introduction' => 'This are the payment details we\'ll use to charge for your lessons.',
            ],
        ],

    ],

    'admin' => [

        'dashboard' => [
            'heading'      => 'Dashboard',
            'introduction' => '',
        ],

        'tutors' => [
            'heading'      => 'Tutors',
            'introduction' => '',
        ],

        'students' => [
            'heading'      => '',
            'introduction' => '',
        ],

        'messages' => [
            'heading'      => '',
            'introduction' => '',
        ],

        'lessons' => [
            'heading'      => '',
            'introduction' => '',
        ],

    ],

];
