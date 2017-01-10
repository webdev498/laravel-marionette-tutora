<?php

namespace App\Schedules;

use App\Database\Model;
use Carbon\Carbon;

class NotificationSchedule extends Model
{
    const TYPE = null;
    
    protected $table = 'notification_schedules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'send_at',
        'last_sent_at',
        'last_sent_type',
        'count',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'send_at', 'last_sent_at'];
    

    public function getSendAt($period)
    {
        $date = Carbon::now();
        $date->modify($period);
        return $date;
    }

    public function getNext()
    {
        $last = $this->instantiateLast($this->last_notification_name);
        $last->setProperties($this->user);
        $next = $last->getNextNotification();
        $next->setProperties($this->user);
        return $next;
    }

    public function setNext()
    {
        $last = $this->instantiateLast($this->last_notification_name);

        $this->last_notification_name = $last->getNextNotificationType();
        $this->send_at = $last->getNextNotificationDate();

        return $this;
    }

    protected function instantiateLast($notificationType)
    {
        $className = __NAMESPACE__ . '\\'. $this->notificationsFolder .'\\'. $notificationType;
        
        $notification = app($className);
        $notification->setProperties($this->user);

        return $notification;
    }



    //////////////////////////////////////////////////////////////////////////////////////////////


    /**
     * Override the parent newQuery class to query only get the correct type.
     * 
     * @return Builder $query
     */
    public function newQuery()
    {
        $query = parent::newQuery();
        if (static::TYPE !== null) $query->where('type', '=', static::TYPE);
        return $query;
    }

    /**
     * Create a new model instance that is existing.
     *
     * @param  array  $attributes
     * @param  string|null  $connection
     * @return static
     */
    public function newFromBuilder($attributes = array(), $connection = null)
    {
        $class = __NAMESPACE__ . '\\'. ucwords($attributes->type) . 'Schedule';
        $instance = new $class;
        $instance->exists = true;
        $instance->setRawAttributes((array) $attributes, true);
        return $instance;
    }  

    /**
     * The notification schedule belongs to a user.
     *
     * @return BelongsTo
     */
    public function user()
    {
         return $this->belongsTo('App\User', 'user_id');
    }
}
