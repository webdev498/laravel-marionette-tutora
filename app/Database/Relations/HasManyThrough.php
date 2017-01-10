<?php

namespace App\Database\Relations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasManyThrough as BaseHasManyThrough;

class HasManyThrough extends BaseHasManyThrough
{
    /**
     * The parent key on the relationship.
     *
     * @var string
     */
    protected $parentKey;

    /**
     * Create a new has many through relationship instance.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $farParent
     * @param  \Illuminate\Database\Eloquent\Model  $parent
     * @param  string  $firstKey
     * @param  string  $secondKey
     * @param  string  $localKey
     * @param  string  $parentKey
     * @return void
     */
    public function __construct(
        Builder $query,
        Model   $farParent,
        Model   $parent,
        $firstKey,
        $secondKey,
        $localKey,
        $parentKey
    ) {
        $this->localKey  = $localKey;
        $this->firstKey  = $firstKey;
        $this->secondKey = $secondKey;
        $this->farParent = $farParent;
        $this->parentKey = $parentKey;

        parent::__construct($query, $farParent, $parent, $firstKey, $secondKey, $localKey);
    }

    public function getQualifiedParentKeyName()
    {
        return $this->parent->getTable().'.'.$this->parentKey;
    }
}
