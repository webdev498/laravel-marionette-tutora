<?php

namespace App\Repositories\Traits;

trait ChainableEloquentRepositoryTrait
{
    /**
     * Select only the last message line sent.
     *
     * @param  MorphMany $query
     * @return MorphMany
     */
    
    protected function newChainableQuery($object)
    {
        $this->query  = $object->newQuery();
        return $this;
    }

    protected function takePageChainable($page, $perPage)
    {
        $this->query->takePage($page, $perPage);

        return $this;
    }

    protected function withChainable($with)
    {
        $this->query->with($with);

        return $this;
    }
}
