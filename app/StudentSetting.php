<?php

namespace App;

use App\Database\Model;

class StudentSetting extends Model
{
    public static function make()
    {
    	$studentSettings = new static();
    	$studentSettings->receive_requests = 0;
    	$studentSettings->retry_failed_payments = 1;
    	$studentSettings->send_failed_payment_notifications = 1;


    	return $studentSettings;
    }
}
