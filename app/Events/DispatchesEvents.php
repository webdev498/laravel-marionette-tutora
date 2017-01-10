<?php namespace App\Events;

trait DispatchesEvents
{

    /**
     * Dispatch events by firing the appropriate event.
     *
     * @param array $events
     * @return void
     */
    protected function dispatch($event)
    {
        if (is_array($event)) {
            return array_map(
                [$this, __FUNCTION__],
                array_filter($event)
            );
        }

        loginfo('Event "'.get_class($event).'" has fired');
        event($event);
        loginfo('Event "'.get_class($event).'" has finished');
    }

    /**
     * Dispatch and release events on the given entity
     *
     * @param mixed $entity
     * @return mixed
     */
    protected function dispatchFor($entity)
    {
    
        if (is_array($entity)) {
            return array_map(
                [$this, __FUNCTION__],
                array_filter($entity)
            );
        }
        $this->dispatch($entity->releaseEvents());
    }

}
