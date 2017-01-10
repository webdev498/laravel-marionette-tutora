<?php

return [

    'status' => [
        'pending' => [
            'tutor' => [
                'short' => 'Pending',
                'long'  => 'Waiting for the student to confirm the lesson booking.',
            ],
            'student' => [
                'short' => 'Pending',
                'long'  => 'Waiting for you to confirm the lesson booking.',
            ],
        ],

        'confirmed' => [
            'tutor' => [
                'short' => 'Confirmed',
                'long'  => 'The student has confirmed the lesson booking.',
            ],
            'student' => [
                'short' => 'Confirmed',
                'long'  => 'You have confirmed the lesson booking.',
            ],
        ],

        'authorisation_pending' => [
            'tutor' => [
                'short' => 'Confirmed*',
                'long'  => 'The student has confirmed the lesson booking, however, payment is pending.',
            ],
            'student' => [
                'short' => 'Authorisation pending',
                'long'  => 'You have confirmed the lesson booking, however, payment is pending.',
            ],
        ],

        'authorisation_failed' => [
            'student' => [
                'short' => 'Payment failed',
                'long'  => 'Payment authorisation failed.',
            ],
        ],

        'payment_pending' => [
            'student' => [
                'short' => 'Payment pending',
                'long'  => 'Payment pending.',
            ],
        ],


        'payment_failed' => [
            'student' => [
                'short' => 'Payment failed',
                'long'  => 'Payment failed.',
            ],
        ],

        'completed' => [
            'tutor' => [
                'short' => 'Completed',
                'long'  => 'The lesson has taken place.',
            ],
            'student' => [
                'short' => 'Completed',
                'long'  => 'The lesson has taken place.',
            ],
        ],

        'cancelled' => [
            'tutor' => [
                'short' => 'Cancelled',
                'long'  => 'This lesson has been cancelled.',
            ],
            'student' => [
                'short' => 'Cancelled',
                'long'  => 'This lesson has been cancelled.',
            ],
        ],
    ],

];
