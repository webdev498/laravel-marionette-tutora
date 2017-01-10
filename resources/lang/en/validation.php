<?php

return [


    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    "accepted"             => "The :attribute must be accepted.",
    "active_url"           => "The :attribute is not a valid URL.",
    "after"                => "The :attribute must be a date after :date.",
    "alpha"                => "The :attribute may only contain letters.",
    "alpha_dash"           => "The :attribute may only contain letters, numbers, and dashes.",
    "alpha_num"            => "The :attribute may only contain letters and numbers.",
    "array"                => "The :attribute must be an array.",
    "before"               => "The :attribute must be a date before :date.",
    "between"              => [
        "numeric" => "The :attribute must be between :min and :max.",
        "file"    => "The :attribute must be between :min and :max kilobytes.",
        "string"  => "The :attribute must be between :min and :max characters.",
        "array"   => "The :attribute must have between :min and :max items.",
    ],
    "boolean"              => "The :attribute field must be true or false.",
    "confirmed"            => "The :attribute confirmation does not match.",
    "date"                 => "The :attribute is not a valid date.",
    "date_format"          => "The :attribute does not match the format :format.",
    "different"            => "The :attribute and :other must be different.",
    "digits"               => "The :attribute must be :digits digits.",
    "digits_between"       => "The :attribute must be between :min and :max digits.",
    "email"                => "The :attribute must be a valid email address.",
    "filled"               => "The :attribute field is required.",
    "exists"               => "The selected :attribute is invalid.",
    "image"                => "The :attribute must be an image.",
    "in"                   => "The selected :attribute is invalid.",
    "integer"              => "The :attribute must be an integer.",
    "ip"                   => "The :attribute must be a valid IP address.",
    "max"                  => [
        "numeric" => "The :attribute may not be greater than :max.",
        "file"    => "The :attribute may not be greater than :max kilobytes.",
        "string"  => "The :attribute may not be greater than :max characters.",
        "array"   => "The :attribute may not have more than :max items.",
    ],
    "mimes"                => "The :attribute must be a file of type: :values.",
    "min"                  => [
        "numeric" => "The :attribute must be at least :min.",
        "file"    => "The :attribute must be at least :min kilobytes.",
        "string"  => "The :attribute must be at least :min characters.",
        "array"   => "The :attribute must have at least :min items.",
    ],
    "not_in"               => "The selected :attribute is invalid.",
    "numeric"              => "The :attribute must be a number.",
    "no_phone"             => "Please don't exhange phone numbers prior to arranging a lesson.",
    "no_email"             => "Please don't exhange email addresses prior to arranging a lesson.",
    "message_no_contact"   => "Please don't exchange contact details prior to arranging a lesson. We will share your contact details once you have confirmed a lesson. Keeping messages on the website helps us find a tutor for every student.",
    "no_contact"           => "Please don't exchange contact details prior to arranging a lesson. We will share your contact details once you have confirmed a lesson. Keeping messages on the website helps us find a tutor for every student.",
    "regex"                => "The :attribute format is invalid.",
    "required"             => "The :attribute field is required.",
    "required_if"          => "The :attribute field is required when :other is :value.",
    "required_with"        => "The :attribute field is required when :values is present.",
    "required_with_all"    => "The :attribute field is required when :values is present.",
    "required_without"     => "The :attribute field is required when :values is not present.",
    "required_without_all" => "The :attribute field is required when none of :values are present.",
    "same"                 => "The :attribute and :other must match.",
    "size"                 => [
        "numeric" => "The :attribute must be :size.",
        "file"    => "The :attribute must be :size kilobytes.",
        "string"  => "The :attribute must be :size characters.",
        "array"   => "The :attribute must contain :size items.",
    ],
    "unique"               => "The :attribute has already been taken.",
    "url"                  => "The :attribute format is invalid.",
    "timezone"             => "The :attribute must be a valid zone.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'start' => [
            'after' => 'The start must be at least :date from now',
        ],
        'addresses' => [
            'default' => [
                'line_1' => [
                    'required' => 'The street field is required',
                ],
                'line_2' => [
                    'required' => 'The town field is required',
                ],
                'line_3' => [
                    'required' => 'The county field is required',
                ],
                'postcode' => [
                    'required' => 'The postcode field is required',
                ]
            ],
            'billing' => [
                'line_1' => [
                    'required' => 'The street field is required',
                ],
                'line_2' => [
                    'required' => 'The town field is required',
                ],
                'line_3' => [
                    'required' => 'The county field is required',
                ],
                'postcode' => [
                    'required' => 'The postcode field is required',
                ]
            ],
        ],

        'profile' => [
            'tagline' => [
                'required' => 'The tagline field is required',
                'max' => "Please keep your tagline below :max characters.",
            ],
            'rate' => [
                'required' => 'The rate field is required',
                'numeric' => "The rate field must be a number.",
            ],
            'travel_radius' => [
                'required' => 'The radius field is required',
            ],

            'bio' => [
                'required' => 'The bio field is required',
                'min' => "Your Bio is a little short. Please write at least :min characters - the more you write, the more information potential clients will have about you.",
            ],
            'short_bio' => [
                'max' => "The short bio field may not be greater than :max characters.",
            ],
        ],

        'quiz' => [
            'wrong_answer' => 'Wrong answer',
            'correct_answer' => 'Correct',
        ],

        'universities' => array_map(function() {
            return [
                'university' => [
                    'required' => 'The location field is required',
                ],
                'level' => [
                    'required' => 'The level field is required',
                ],
                'subject' => [
                    'required' => 'The subject field is required',
                ]
            ];
        }, range(0, 20)),

        'alevels' => array_map(function() {
            return [
                'college' => [
                    'required' => 'The location field is required',
                ],
                'grade' => [
                    'required' => 'The level field is required',
                ],
                'subject' => [
                    'required' => 'The subject field is required',
                ]
            ];
        }, range(0, 20)),

        'others' => array_map(function() {
            return [
                'location' => [
                    'required' => 'The location field is required',
                ],
                'grade' => [
                    'required' => 'The grade field is required',
                ],
                'subject' => [
                    'required' => 'The subject field is required',
                ]
            ];
        }, range(0, 20)),

        'booking' => [
            'rate' => [
                'between' => 'You can only set your hourly rate between £:min - £:max'
            ]
        ],

        'subjects' => [
            'name' => [
                'exists' => 'Subject not found'
            ]
        ]


    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
