<?php namespace App\Events;

trait RaisesEvents
{

    /**
     * A collection of raised events
     *
     * @var array
     */
    protected $events = [];

    /**
     * Raise an event
     *
     * @param $event mixed
     * @return void
     */
    public function raise($event)
    {
        $this->events[] = $event;
    }

    /**
     * Return and reset the raised events
     *
     * @return array
     */
    public function releaseEvents()
    {
        $events = $this->events;

        $this->events = [];

        return $events;
    }

}
