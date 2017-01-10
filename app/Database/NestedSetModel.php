<?php

namespace App\Database;

use Kalnoy\Nestedset\Node;

class NestedSetModel extends Node
{
    /**
     * The name of the 'lft' column.
     *
     * @var string
     */
    const LFT = 'left';

    /**
     * The name of the 'rgt' column.
     *
     * @var string
     */
    const RGT = 'right';

    /**
     * The children of this model are stored here
     * when using a the ->toTree() method on
     * the NestedSetCollection
     */
    public $children;

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return NestedSetCollection
     */
    public function newCollection(Array $models = [])
    {
        return new NestedSetCollection($models);
    }

    /**
     * Mutate the children
     *
     * @return Collection or array
     */
    public function getChildrenAttribute()
    {
        return $this->children ? collect($this->children) : [];
    }

}
