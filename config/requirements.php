<?php

return [
    [
        'name'    => App\UserRequirement::TAGLINE,
        'section' => App\UserRequirement::PROFILE,
        'for'     => App\UserRequirement::PROFILE_INFORMATION,
    ],
    [
        'name'    => App\UserRequirement::RATE,
        'section' => App\UserRequirement::PROFILE,
        'for'     => App\UserRequirement::PROFILE_INFORMATION,
    ],
    [
        'name'    => App\UserRequirement::TRAVEL_POLICY,
        'section' => App\UserRequirement::PROFILE,
        'for'     => App\UserRequirement::PROFILE_INFORMATION,
    ],
    [
        'name'    => App\UserRequirement::BIO,
        'section' => App\UserRequirement::PROFILE,
        'for'     => App\UserRequirement::PROFILE_INFORMATION,
    ],
    [
        'name'    => App\UserRequirement::PROFILE_PICTURE,
        'section' => App\UserRequirement::PROFILE,
        'for'     => App\UserRequirement::PROFILE_INFORMATION,
    ],
    [
        'name'    => App\UserRequirement::SUBJECTS,
        'section' => App\UserRequirement::PROFILE,
        'for'     => App\UserRequirement::PROFILE_INFORMATION,
    ],
    [
        'name'    => App\UserRequirement::QUALIFICATIONS,
        'section' => App\UserRequirement::PROFILE,
        'for'     => App\UserRequirement::PROFILE_INFORMATION,
    ],
    [
        'name'    => App\UserRequirement::QUIZ_QUESTIONS,
        'section' => App\UserRequirement::QUIZ,
        'for'     => App\UserRequirement::PROFILE_SUBMIT,
    ],
    [
        'name'        => App\UserRequirement::QUALIFIED_TEACHER_STATUS,
        'section'     => App\UserRequirement::OTHER,
        'for'         => App\UserRequirement::ANY,
        'is_optional' => true,
    ],
    [
        'name'    => App\UserRequirement::IDENTIFICATION,
        'section' => App\UserRequirement::ACCOUNT,
        'for'     => App\UserRequirement::PROFILE_INFORMATION,
    ],
    [
        'name'        => App\UserRequirement::BACKGROUND_CHECK,
        'section'     => App\UserRequirement::OTHER,
        'for'         => App\UserRequirement::ANY,
        'is_optional' => true,
    ],
    [
        'name'    => App\UserRequirement::PAYMENT_DETAILS,
        'section' => App\UserRequirement::ACCOUNT,
        'for'     => App\UserRequirement::PAYOUTS,
    ],
    [
        'name'        => App\UserRequirement::PERSONAL_VIDEO,
        'section'     => App\UserRequirement::OTHER,
        'for'         => App\UserRequirement::ANY,
        'is_optional' => true
    ]
];
