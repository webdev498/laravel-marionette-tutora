<?php namespace App\Database;

use DateTime;
use App\Events\RaisesEvents;
use Illuminate\Support\Collection;
use App\Database\Scopes\TakePageTrait;
use App\Database\Scopes\OrderByColumnTrait;
use App\Database\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Database\Exceptions\ResourceNotPersistedException;


class Model extends Eloquent
{

    use RaisesEvents, TakePageTrait, OrderByColumnTrait;

    public function pullAttribute($key)
    {
        return array_pull($this->attributes, $key);
    }

    public function removeAttribute($key)
    {
        return array_forget($this->attributes, $key);
    }

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     */
    public function fill(array $attributes)
    {
        return parent::fill(
            $this->cleanAttributes($attributes)
        );
    }

    public function cleanAttributes(array $attributes)
    {
        return array_map(function ($value) {
            return $value === '' ? null : $value;
        }, array_filter($attributes, function ($value) {
            return $value !== null;
        }));
    }

    /**
     * Define a has-many-through relationship.
     *
     * @param  string  $related
     * @param  string  $through
     * @param  string|null  $firstKey
     * @param  string|null  $secondKey
     * @param  string|null  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function hasManyThrough(
        $related,
        $through,
        $firstKey  = null,
        $secondKey = null,
        $localKey  = null,
        $parentKey = null
    ) {
        $through   = new $through;
        $firstKey  = $firstKey ?: $this->getForeignKey();
        $secondKey = $secondKey ?: $through->getForeignKey();
        $localKey  = $localKey ?: $this->getKeyName();
        $parentKey = $parentKey ?: $this->getKeyName();

        return new HasManyThrough(
            (new $related)->newQuery(),
            $this,
            $through,
            $firstKey,
            $secondKey,
            $localKey,
            $parentKey
        );
    }

    public function scopeWhereEqualTo($query, $column, $values)
    {
        if (is_array($values))
        {
            return $query->whereIn($column, $values);
        }

        return $query->where($column, $values);
    }

    public function scopeCreatedBetween($query, DateTime $start, DateTime $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    /**
     * Generate a uuid, ensuring it is, in fact, unique to the model
     *
     * @return string
     */
    public static function generateUuid()
    {
        do {
            $uuid = str_uuid();
        } while (self::where('uuid', $uuid)->count() > 0);
        return $uuid;
    }
}
