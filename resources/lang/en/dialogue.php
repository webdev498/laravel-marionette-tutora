<?php

return [

    'auth' => [

        'login'   => [
            'heading' => 'Already have an account?',
        ],
        'sign_up' => [
            'heading' => 'Sign Up',
        ],

    ],

    'register' => [

        'student' => [
            'heading' => [
                'default' => 'Request a Tutor',
                'message' => 'Sign up to send message',
            ],

            'placeholder' => [
                'password' => [
                    'default' => 'Password',
                    'message' => 'Create Password',
                ],
            ],

            'submit' => [
                'default' => 'Submit Request',
                'message' => 'Send Message',
            ]
        ],

        'tutor' => [
            'heading' => 'Want to become a Tutor?',
        ],

    ],

    'booking' => [

        'create' => [
            'heading'      => 'Book a new lesson',
            'trial_heading' => 'Book a new trial lesson',
            'introduction' => "You can book a new lesson with any of the students you have exchanged messages with.  To book a lesson on the same day every week, be sure to click 'Repeat Weekly'.",
            'trial_introduction' => "You can book a trial lesson with any of your students. We recommend charging 50% of your hourly rate, but you can charge as little as £5 for a trial lesson.",
        ],

        'create_for_tutor' => [
            'heading'      => 'Book a new lesson',
            'trial_heading'=> 'Book a new trial lesson',
            'introduction' => "You can book a new lesson with any of the students tutor have exchanged messages with.  To book a lesson on the same day every week, be sure to click 'Repeat Weekly'.",
            'trial_introduction' => "Book a trial lesson. Price can be as low as £5/hr",
        ],

        'edit' => [
            'heading'      => 'Edit a booking',
            'introduction' => '',
        ],

        'cancel' => [
            'heading'        => 'Are you sure you want to cancel this lesson?',
            'introduction'   => '',
            'charge_warning' => 'This lesson is due to take place in less than 12 hours time. <strong>You will be charged 50% of the lesson price for cancelling this lesson.</strong>',
        ],

        'pay' => [
            'heading'      => 'Retry a payment',
            'introduction' => 'Whenever there\'s a problem with either preparing or taking a payment, we\'ll ask you to retry your payment here.',
        ],

        'refund' => [
            'heading'      => 'Refund a payment',
            'introduction' => 'Are you sure you want to refund this payment?',
        ],

        'review' => [
            'heading'      => 'Leave a review for this tutor',
            'introduction' => 'Our tutors really value your feedback.  Please take a minute to write a few lines about your lessons with this tutor.',
        ],

    ],

    'message' => [

        'create' => [
            'title'        => 'Send message',
            'introduction' => "Make sure you mention the subject and where you live, but don't include any phone numbers or email addresses.",
        ],
    ],

    'profile' => [

        'tagline' => [
            'edit' => [
                'heading'      => 'Edit your tagline',
                'introduction' => 'A short, snappy summary to promote yourself e.g. "Enthusiastic GCSE Maths & English Tutor"',
            ],
        ],

        'rate' => [
            'edit' => [
                'heading'      => 'Edit your rate',
                'introduction' => 'Set your hourly rate of pay.  Keep in mind that we take a small commission from this base rate.',
            ],
        ],

        'bio' => [
            'edit' => [
                'heading'      => 'Edit your bio',
                'introduction' => 'Write a professional personal statement, which sets out your experience and teaching style.  This is your opportunity to sell yourself to potential clients.',
            ],
        ],

        'subjects' => [
            'edit' => [
                'heading'      => 'Edit your subjects',
                'introduction' => ' Add the subjects and ALL levels that your are confident to tutor at. Scroll to the right to see all subjects that we offer.',
            ],
        ],

        'qualifications' => [
            'edit' => [
                'heading'      => 'Edit your qualifications',
                'introduction' => 'Add any qualifications you have achieved, such as A-levels or Degrees.',
            ],
        ],

        'qts' => [
            'edit' => [
                'heading'      => 'Edit your QTS',
                'introduction' => 'If you have Qualified Teacher Status, please add it here.',
            ],
        ],

        'background_check' => [
            'dbs_check'     => [
                'edit' => [
                    'heading'         => 'DBS Check',
                    'introduction'    => 'To provide us a DBS Check, please EITHER...',
                    'note'            => 'Please note',
                    'note_text'       => 'Other users will NOT be able to view your DBS check or any of your personal details.',
                    'pending_status'  => 'Your background check is waiting approval',
                    'approved_status' => 'Your background check was approved',
                ],
            ],
            'already_exist' => 'DBS Check already submitted',
        ],

        'travel_policy' => [
            'edit' => [
                'heading'      => 'Edit your travel policy',
                'introduction' => 'State how far you are willing to travel away from a given address.',
            ],
        ],

        'quiz' => [
            'introduction' => [
                'heading_new_tutors'           => 'Thanks for choosing to Tutor with Tutora!',
                'introduction_new_tutors'      => 'Before you go live on the site, we want to make sure that you know how it all works, so you get the best experience possible! Read the details below and then complete the quiz that follows. Don\'t worry, it only takes a minute and will make sure that you get the most from tutoring with us.',
                'heading_existing_tutors'      => 'Sorry to interupt...we just need a few moments of your time',
                'introduction_existing_tutors' => 'We hope you’re enjoying tutoring and using our site to find new students. We want to make sure that everyone understands how to use the site properly, so please take a couple of minutes to read how the site works, and to complete a short quiz.',
                'submit'                       => 'Got it...now take me to the quiz',
            ],
            'edit'         => [
                'heading' => 'Please complete our short quiz',
            ],
        ],

        'live' => [
            'create' => [
                'heading'      => 'Important - Please Read',
                'introduction' => "Thanks for choosing Tutora! To prepare you to be a successful tutor, we’re going to walk you through a few quick points about how Tutora works."
            ],

            'edit' => [
                'heading'      => "You're currently offline",
                'introduction' => "Take your profile online and let thousands of students find you!",
            ],

            'destroy' => [
                'heading'      => "You're currently live",
                'introduction' => "Are you sure you want to take your profile offline?",
            ]
        ],

        'video' => [
            'record' => [
                'heading'      => 'Create a short video',
                'introduction' => "Here you can record a short 3-minute clip highlighting your tutoring experience. We suggest talking a little about yourself, your tutoring experience, and how you can help potential students.",


            ],
            'edit'   => [
                'heading'      => 'Re-record or delete your current video',
                'introduction' => 'If you need to edit your video, you can re-record it below.'
            ]
        ]

    ],

    'tutors' => [
        'delete'  => [
            'heading'     => 'Delete the Tutor',
            'description' => 'This tutor will be set to deleted and all sensitive data will be removed, this includes the identity documents and their stripe account'
        ],
        'block'   => [
            'heading'     => 'Block the Tutor',
            'description' => 'This tutor will be blocked and will lose the ability to login on the site. This action CAN be undone'
        ],
        'unblock' => [
            'heading'     => 'Unblock the Tutor',
            'description' => 'This tutor will be unblocked and will get the ability to login on the site'
        ],
    ],

    'students' => [
        'delete'  => [
            'heading'     => 'Delete the Student',
            'description' => 'This student will be set to deleted and all sensitive data will be removed, this includes their stripe account'
        ],
        'block'   => [
            'heading'     => 'Block the Student',
            'description' => 'This student will be blocked and will lose the ability to login on the site. This action CAN be undone'
        ],
        'unblock' => [
            'heading'     => 'Unblock the Student',
            'description' => 'This student will be unblocked and will get the ability to login on the site'
        ],
    ],

    'background_checks' => [
        'remove' => [
            'heading'     => 'You are going to remove background check',
            'description' => 'Are you sure?',
        ],
    ],

    'review' => [
        'cancel' => [
            'heading' => 'Are you sure to delete this review?',
            'submit' => 'Delete review'
        ]
    ]

];
