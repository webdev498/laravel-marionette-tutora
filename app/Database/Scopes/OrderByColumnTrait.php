<?php namespace App\Database\Scopes;

trait OrderByColumnTrait
{

    /**
     * Order results by a field.
     *
     * @param $query
     * @param $page
     * @param $perPage
     *
     * @return mixed
     */
    public function scopeOrderByColumn($query, $column, $order)
    {
        if ( ! $column ) {
            return $query;
        }

        return $query->orderBy($column, $order);
    }

}
