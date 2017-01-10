<?php

namespace App\Presenters;

use App\Presenters\MessagePresenter;
use App\Presenters\UserPresenter;
use App\Transgression;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class TransgressionPresenter extends TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'user',
        'message',
    ];

    /**
     * Turn this object into a generic array
     *
     * @param  Transgression $transgression
     * @return array
     */
    public function transform(Transgression $transgression)
    {
        return [
            'id'         => (integer) $transgression->id,
            'body'       => (string)  $transgression->body,
            'short_body' => (string)  str_limit($transgression->body, 40),
            'created_at'  => $this->formatTime($transgression->created_at),
        ];
    }

    /**
     * Include User
     *
     * @param  Transgression $transgression
     * @return Item
     */
    protected function includeUser(Transgression $transgression)
    {
        return $this->item($transgression->user, new UserPresenter());
    }

    /**
     * Include Message
     *
     * @param  Transgression $transgression
     * @return Item
     */
    protected function includeMessage(Transgression $transgression)
    {
        return $this->item($transgression->message, new MessagePresenter());
    }


    protected function formatTime($time)
    {
        return [
            'value' => $time->format('Y-m-d'),
            'short' => $time->format('d/m/Y'),
            'long'  => $time->format('jS F Y'),
        ];
    }

}

