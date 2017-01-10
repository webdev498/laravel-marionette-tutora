<?php

return [

    // NOTE
    // Duplicates will be deleted.

    'fields_needed' => [
        'identification' => 'Identification',
        'legal_entity' => [
            'dob' => [
                'day'   => 'Date of birth',
                'month' => 'Date of birth',
                'year'  => 'Date of birth'
            ],
            'address' => [
                'line1'       => 'Billing address',
                'city'        => 'Billing address',
                'postal_code' => 'Billing address',
            ],
        ],
        'bank_account' => 'Bank account',
        'external_account' => 'Bank account',
    ],

];
