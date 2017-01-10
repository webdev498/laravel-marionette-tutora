<?php

namespace App;

use Vinkla\Hashids\Facades\Hashids;
use App\Database\Model;

class UserSubscription extends Model
{


    const MARKETING     = 'marketing';
    const SIGNUP        = 'signup';
    const JOBS          = 'job_opportunities';
    const MESSAGE_EMAIL = 'message_email';
    const MESSAGE_SMS   = 'message_sms';

    protected $subscriptions = [
        'marketing'         => self::MARKETING,
        'signup'            => self::SIGNUP,
        'job_opportunities' => self::JOBS,
        'message_email'     => self::MESSAGE_EMAIL,
        'message_sms'       => self::MESSAGE_SMS
    ];


    public static function blank()
    {
        $subscription = new static();

        $subscription->any = 1;
        $subscription->marketing = 1;
        $subscription->signup = 1;
        $subscription->job_opportunities = 1;
        $subscription->message_email = 1;
        $subscription->message_sms = 1;
        $subscription->job_opportunities_frequency = 'daily';
        
        return $subscription;
    }

	public function generateToken()
	{
        return HashIds::connection('subscriptions')->encode($this->user->id);
	}


    public function unsubscribe($list)
    {
        if ($list = $this->getList($list)) {
            $this->{$list} = 0;
            return $this;
        }
        return false;
    }

    public function subscribe($list)
    {
        if ($list = $this->getList($list)) {
            $this->{$list} = 1;
            return $this;
        }
        return false;
    }

    public function isSubscribed($list)
    {
        if ($list = $this->getList($list) && $this->{$list} == 1) {
            return true;
        }
        return false;
    }


    /**
     * A subscription belongs to a user
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }


    protected function getList($list) {

        if (isset($this->subscriptions[$list])) {
            return $this->subscriptions[$list];
        }
        return false;
    }
}
