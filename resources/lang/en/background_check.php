<?php

return [
    'background_check' => 'I have a Background check.',
    'dbs'              => 'I have a DBS check.',
    'dbs_update'       => 'I have a DBS Update check.',

    'dbs_admin_statuses' => [
        \App\UserBackgroundCheck::ADMIN_STATUS_PENDING  => 'Pending',
        \App\UserBackgroundCheck::ADMIN_STATUS_REJECTED => 'Rejected',
        \App\UserBackgroundCheck::ADMIN_STATUS_APPROVED => 'Approved',
    ],

    'dbs_reject_reasons' => [
        \App\UserBackgroundCheck::DBS_REJECT_REASON_OUT_OF_DATE => 'Certificates out of date',
        \App\UserBackgroundCheck::DBS_REJECT_REASON_NO_COLOUR   => 'Not in colour',
        \App\UserBackgroundCheck::DBS_REJECT_REASON_NOT_CLEAR   => 'Not clear enough',
        \App\UserBackgroundCheck::DBS_REJECT_REASON_NOT_WHOLE   => 'Not whole document',
        \App\UserBackgroundCheck::DBS_REJECT_REASON_CUSTOM      => 'Custom (enter reason)',
    ],

    'dbs_update_reject_reasons' => [
        \App\UserBackgroundCheck::DBS_UPDATE_REJECT_REASON_NOT_FOUND  => 'Can\'t find a record',
        \App\UserBackgroundCheck::DBS_UPDATE_REJECT_REASON_SERVICE_ID => 'Update service ID not certificate number',
    ],

    'background_check_types' => [
        \App\UserBackgroundCheck::TYPE_DBS_UPDATE  => 'DBS Update',
        \App\UserBackgroundCheck::TYPE_DBS_CHECK => 'DBS',
    ],
];