<?php

return [

    'roles' => [
        \App\Role::TUTOR,
        \App\Role::STUDENT,
        \App\Role::ADMIN
    ],

    'users'     => environment('testing') ? 2  : 100,
    'addresses' => environment('testing') ? 10 : 500,
    'lessons'   => environment('testing') ? 0  :  1,

    'subjects' => [
        [
            'name'     => 'Maths',
            'children' => [
                [
                    'name'     => 'Maths',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Further Maths',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Mechanics',
                    'children' => [
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Statistics',
                    'children' => [
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' =>  'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Pure Maths',
                    'children' => [
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
            ],
        ],
        [
            'name'     => 'English',
            'children' => [
                [
                    'name'     => 'English',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'English Literature',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Phonics',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                    ],
                ],
                [
                    'name'     => 'Reading Comprehension',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                    ],
                ],
                [
                    'name'     => 'Spelling, Punctuation and Grammar',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                    ],
                ],
            ],
        ],
        [
            'name'     => 'Science',
            'children' => [
                [
                    'name'     => 'Combined Sciences',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                    ],
                ],
                [
                    'name'     => 'Biology',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Chemistry',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Physics',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Astronomy',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Environmental Science',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Geology',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
            ],
        ],
        [
            'name'     => 'Languages',
            'children' => [
                [
                    'name'     => 'Afrikaans',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Arabic',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Bengali',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Bulgarian',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Cantonese',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Catalan',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Croatian',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Czech',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Danish',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Dutch',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'English as a Foreign Language',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Estonian',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Farsi',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Finnish',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'French',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Gaelic',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'German',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Greek (Classical)',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Greek (Modern)',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Gujarati',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Hebrew',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Hindi',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Hungarian',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Irish',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Italian',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Japanese',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Kashmiri',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Korean',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Latin',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Lithuanian',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Malay',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Mandarin',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Norwegian',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Persian',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Polish',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Portuguese',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Punjabi',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Romanian',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Russian',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Serbian',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Sign Language',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Slovak',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Spanish',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Swedish',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Tamil',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Telugu',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Thai',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Turkish',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Ukrainian',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Urdu',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Vietnamese',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
                [
                    'name'     => 'Welsh',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                    ],
                ],
            ],
        ],
        [
            'name'     => 'Humanities (including the arts) and Social Sciences',
            'children' => [
                [
                    'name'     => 'Art',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Anthropology',
                    'children' => [
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Archaeology',
                    'children' => [
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Citizenship',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Classics',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],

                [
                    'name'     => 'Design and Technology',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Electronics',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Food Technology',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Graphic Design',
                    'children' => [
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Manufacturing',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Resistant Materials',
                    'children' => [
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Systems and Control',
                    'children' => [
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Textiles',
                    'children' => [
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Drama',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Economics',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Film Studies',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Gender and Sexuality Studies',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Geography',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'History',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'History of Art',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Home Economics',
                    'children' => [
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Child Development',
                    'children' => [
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Food and Nutrition',
                    'children' => [
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Humanities',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Performing Arts',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Philosophy',
                    'children' => [
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Photography',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Psychology',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Religious Education',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Sociology',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name' => 'Politics',
                    'children' => [
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name' => 'Modern Studies',
                ],
            ],
        ],

        [
            'name'     => 'Business and Professional Studies',
            'children' => [
                [
                    'name'     => 'Accounting',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Agriculture',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Business Studies',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Construction',
                    'children' => [
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Engineering',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'General Studies',
                    'children' => [
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Health and Social Care',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Hospitality',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Journalism',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Law',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Leisure Studies',
                    'children' => [
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Leisure and Tourism',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Media Studies',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Medicine',
                    'children' => [
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Neuroscience',
                    'children' => [
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Travel and Tourism',
                    'children' => [
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
            ],
        ],
        [
            'name'     => 'Computing',
            'children' => [
                [
                    'name'     => 'Computing',
                    'children' => [
                        [
                            'name' => 'KS2',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'ICT',
                    'children' => [
                        [
                            'name' => 'KS2',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Programming',
                    'children' => [
                        [
                            'name' => 'KS2',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'IB',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'A-Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name' => 'Adobe Acrobat',
                ],
                [
                    'name' => 'Adobe Dreamweaver',
                ],
                [
                    'name' => 'Adobe Fireworks',
                ],
                [
                    'name' => 'Adobe Flash',
                ],
                [
                    'name' => 'Adobe Illustrator',
                ],
                [
                    'name' => 'Adobe Indesign',
                ],
                [
                    'name' => 'Adobe Lightroom',
                ],
                [
                    'name' => 'Adobe Photoshop',
                ],
                [
                    'name' => 'Adobe Premiere',
                ],
                [
                    'name' => 'Android Development',
                ],
                [
                    'name' => 'Apple',
                ],
                [
                    'name' => 'ASP.net',
                ],
                [
                    'name' => 'Autocad',
                ],
                [
                    'name' => 'Autodesk',
                ],
                [
                    'name' => 'Basic IT Skills',
                ],
                [
                    'name' => 'Computer Graphics',
                ],
                [
                    'name' => 'Computer Literacy',
                ],
                [
                    'name' => 'Computer Programming',
                ],
                [
                    'name' => 'Databases',
                ],
                [
                    'name' => 'HTML',
                ],
                [
                    'name' => 'Information Security',
                ],
                [
                    'name' => 'Information Technology',
                ],
                [
                    'name' => 'Java',
                ],
                [
                    'name' => 'Matlab',
                ],
                [
                    'name' => 'Microsoft Access',
                ],
                [
                    'name' => 'Microsoft Excel',
                ],
                [
                    'name' => 'Microsoft Office',
                ],
                [
                    'name' => 'Microsoft Outlook',
                ],
                [
                    'name' => 'Microsoft Powerpoint',
                ],
                [
                    'name' => 'Microsoft Word',
                ],
                [
                    'name' => 'PHP',
                ],
                [
                    'name' => 'Python',
                ],
                [
                    'name' => 'Ruby',
                ],
                [
                    'name' => 'Search Engine Optimisation',
                ],
                [
                    'name' => 'SQL',
                ],
                [
                    'name' => 'Web Design',
                ],
            ],
        ],
        [
            'name'     => 'Music',
            'children' => [
                [
                    'name'     => 'Music',
                    'children' => [
                        [
                            'name' => 'Primary',
                        ],
                        [
                            'name' => 'KS3',
                        ],
                        [
                            'name' => 'GCSE',
                        ],
                        [
                            'name' => 'AS/A Level',
                        ],
                        [
                            'name' => 'Degree',
                        ],
                    ],
                ],
                [
                    'name'     => 'Music Technology',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Music Theory',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Composition',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Accordian',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Bagpipes',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Banjo',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Bass Guitar',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Bassoon',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Cello',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Clarinet',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Conducting',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Cornet',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Double Bass',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Drums',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Euphonium',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Flugel Horn',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Flute',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'French Horn',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Guitar',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Harmonica',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Harp',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Harpsichord',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Keyboard',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Mandolin',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Oboe',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Organ',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Percussion',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Piano',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Picollo',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Recorder',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Saxophone',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Singing',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Sitar',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Tenor Horn',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Trombone',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Trumpet',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Tuba',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Ukulele',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Viola',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],
                [
                    'name'     => 'Violin',
                    'children' => [
                        [
                            'name' => 'Beginner',
                        ],
                        [
                            'name' => 'Intermediate',
                        ],
                        [
                            'name' => 'Advanced',
                        ],
                    ],
                ],  
            ],
        ],
        [
            'name'     => 'Admissions',
            'children' => [
                [
                    'name' => 'Admissions (Primary)',
                ],
                [
                    'name' => 'Seven Plus (7+)',
                ],
                [
                    'name' => 'Eight Plus (8+)',
                ],
                [
                    'name' => 'Eleven Plus (11+)',
                ],
                [
                    'name' => 'Non-verbal reasoning',
                ],
                [
                    'name' => 'Verbal reasoning',
                ],
                [
                    'name' => 'Common Entrance Admissions',
                ],
                [
                    'name' => 'Classics',
                ],
                [
                    'name' => 'English (Common Entrance)',
                ],
                [
                    'name' => 'Geography (Common Entrance)',
                ],
                [
                    'name' => 'History (Common Entrance)',
                ],
                [
                    'name' => 'Religious Studies (Common Entrance)',
                ],
                [
                    'name' => 'Science (Common Entrance)',
                ],
                [
                    'name' => 'Benenden School admissions',
                ],
                [
                    'name' => 'Charterhouse School admissions',
                ],
                [
                    'name' => 'City of London School admissions',
                ],
                [
                    'name' => 'Dulwich College school admissions',
                ],
                [
                    'name' => 'Eltham College school admissions',
                ],
                [
                    'name' => 'Emmanuel College Wimbledon school admissions',
                ],
                [
                    'name' => 'Eton admission',
                ],
                [
                    'name' => 'Eton College admissions',
                ],
                [
                    'name' => 'Francis Holland School admission',
                ],
                [
                    'name' => 'Godolphin & Latymer School admissions',
                ],
                [
                    'name' => 'Haberdasher\'s Aske\'s School admissions',
                ],
                [
                    'name' => 'Harrow admission',
                ],
                [
                    'name' => 'Harrow School admissions',
                ],
                [
                    'name' => 'Henrietta Barnett School admissions',
                ],
                [
                    'name' => 'Highgate School admissions',
                ],
                [
                    'name' => 'James Allen\'s Girls\' School admissions (JAGS)',
                ],
                [
                    'name' => 'King\'s College Wimbledon school admissions',
                ],
                [
                    'name' => 'Lady Eleanor Holles School admissions',
                ],
                [
                    'name' => 'Latymer School admissions',
                ],
                [
                    'name' => 'Lycee Francais Charles de Gaulle school admissions',
                ],
                [
                    'name' => 'Marlborough College school admissions',
                ],
                [
                    'name' => 'Oundle School admissions',
                ],
                [
                    'name' => 'Putney High School admissions',
                ],
                [
                    'name' => 'Queen\'s Gate school admissions',
                ],
                [
                    'name' => 'Radley College school admissions',
                ],
                [
                    'name' => 'Sevenoaks School admissions',
                ],
                [
                    'name' => 'St Paul\'s admission',
                ],
                [
                    'name' => 'St. Mary\'s Ascot school admissions',
                ],
                [
                    'name' => 'St. Paul\'s Girls\' School admissions',
                ],
                [
                    'name' => 'St. Paul\'s School admissions',
                ],
                [
                    'name' => 'The Latymer School admissions',
                ],
                [
                    'name' => 'Tonbridge School admissions',
                ],
                [
                    'name' => 'Wellington College school admissions',
                ],
                [
                    'name' => 'Westminster admission',
                ],
                [
                    'name' => 'Westminster School admissions',
                ],
                [
                    'name' => 'Winchester admission',
                ],
                [
                    'name' => 'Winchester College school admissions',
                ],
                [
                    'name' => 'Wycombe Abbey admissions',
                ],
                [
                    'name' => 'Wycombe Abbey school admissions',
                ],
                [
                    'name' => 'Oxbridge Admissions ',
                ],
                [
                    'name' => 'School Advice',
                ],
                [
                    'name' => 'University Advice',
                ],
            ],
        ],
        [
            'name'     => 'Sports, Dance and Fitness',
            'children' => [
                [
                    'name' => 'Sports',
                ],
                [
                    'name' => 'Archery',
                ],
                [
                    'name' => 'Athletics',
                ],
                [
                    'name' => 'Badminton',
                ],
                [
                    'name' => 'Basketball',
                ],
                [
                    'name' => 'Boxing',
                ],
                [
                    'name' => 'Canoeing',
                ],
                [
                    'name' => 'Climbing',
                ],
                [
                    'name' => 'Cricket',
                ],
                [
                    'name' => 'Cycling',
                ],
                [
                    'name' => 'Fencing',
                ],
                [
                    'name' => 'Football',
                ],
                [
                    'name' => 'Golf',
                ],
                [
                    'name' => 'Gymnastics',
                ],
                [
                    'name' => 'Hockey',
                ],
                [
                    'name' => 'Horseback Riding',
                ],
                [
                    'name' => 'Ice Skating',
                ],
                [
                    'name' => 'Lacrosse',
                ],
                [
                    'name' => 'Martial Arts',
                ],
                [
                    'name' => 'Capoeira',
                ],
                [
                    'name' => 'Judo',
                ],
                [
                    'name' => 'Jujutsu',
                ],
                [
                    'name' => 'Karate',
                ],
                [
                    'name' => 'Kickboxing',
                ],
                [
                    'name' => 'Kung Fu',
                ],
                [
                    'name' => 'Self Defence',
                ],
                [
                    'name' => 'Physical Education',
                ],
                [
                    'name' => 'Rugby',
                ],
                [
                    'name' => 'Sailing',
                ],
                [
                    'name' => 'Skiing',
                ],
                [
                    'name' => 'Snooker',
                ],
                [
                    'name' => 'Squash',
                ],
                [
                    'name' => 'Swimming',
                ],
                [
                    'name' => 'Table Tennis',
                ],
                [
                    'name' => 'Tennis',
                ],
                [
                    'name' => 'Triathlon ',
                ],
                [
                    'name' => 'Volleyball',
                ],
                [
                    'name' => 'Dance',
                ],
                [
                    'name' => 'Ballet',
                ],
                [
                    'name' => 'Ballroom Dance',
                ],
                [
                    'name' => 'Belly Dancing',
                ],
                [
                    'name' => 'Break Dancing',
                ],
                [
                    'name' => 'Salsa Dance',
                ],
                [
                    'name' => 'Street Dance',
                ],
                [
                    'name' => 'Tango',
                ],
                [
                    'name' => 'Tap Dance',
                ],
                [
                    'name' => 'Fitness',
                ],
                [
                    'name' => 'Exercise and Fitness',
                ],
                [
                    'name' => 'Personal Training',
                ],
                [
                    'name' => 'Pilates',
                ],
                [
                    'name' => 'Running',
                ],
                [
                    'name' => 'Weight Lifting',
                ],
                [
                    'name' => 'Yoga',
                ],
                [
                    'name' => 'Chess',
                ],
            ],
        ],
    ],
];
