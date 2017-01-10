<?php

namespace App\Database;

use Cache;
use Kalnoy\Nestedset\Collection;

class NestedSetCollection extends Collection
{

    /**
     * Transform the items collection into a nested collection.
     * Child nodes are stored in ->children
     *
     * @param  Mixed $root
     * @return itemCollection
     */
    public function toTree($root = null)
    {
        // From the the existing collection of items ($this), grab
        // all of the ancestors and store them all in an array of items.
        $items = [];
        foreach ($this as $item) {

            $ancestors = Cache::rememberForever('subject.ancestors.'.$item->id, function() use ($item) {
                return $item->getAncestors();
            });

            foreach ($ancestors as $ancestor) {
                $items[$ancestor->id] = $ancestor;
            }
            $items[$item->id] = $item;
        }

        // Now nest them. This will produce an array with every item
        // being nested from the root item, down.
        $nested = [];
        foreach ($items as &$item) {
            $isNestedRoot = $item->parent_id === null;
            if ($isNestedRoot) {
                $nested[] = &$item;
                continue;
            }

            $isParentExists = isset($items[$item->parent_id]);
            if(!$isParentExists) {continue;}

            if ( ! isset($items[$item->parent_id]->children)) {
                $items[$item->parent_id]->children = [];
            }

            $items[$item->parent_id]->children[] = &$item;
        }

        return new self($nested);
    }

}
