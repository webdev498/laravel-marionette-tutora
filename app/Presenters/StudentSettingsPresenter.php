<?php

namespace App\Presenters;

use App\StudentSetting;
use Carbon\Carbon;

class StudentSettingsPresenter extends AbstractPresenter
{
	/**
     * Turn this object into a generic array
     *
     * @param  StudentSetting $setting
     * @return array
     */
    public function transform(StudentSetting $settings)
    {
        return [
            'receive_requests' => (bool) $settings->receive_requests,
            'retry_failed_payments' => (bool) $settings->retry_failed_payments,
            'send_failed_payment_notifications' => (bool) $settings->send_failed_payment_notifications,
        ];
    }

}