<?php

return [

    // The period in which the lessons have to have
    // been booked in. The number corresponds to the fee.
    //
    // Unit: days
    'period' => 365,

    // The fee ranges. These depend on the number of
    // lessons within the above period.
    //
    'ranges' => [
        [
            'fee'   => '25%',
            'range' => [
                'min' => 0,
                'max' => 99,
            ],
        ],
        [
            'fee'   => '20%',
            'range' => [
                'min' => 100,
                'max' => 299,
            ],
        ],
        [
            'fee'   => '15%',
            'range' => [
                'min' => 300,
            ],
        ],
    ],

    'cancellation' => '50%',

];
