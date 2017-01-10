<?php namespace App\Database\Scopes;

trait TakePageTrait
{

    /**
     * Take a page of results, by setting the limit and offset.
     *
     * @param $query
     * @param $page
     * @param $perPage
     *
     * @return mixed
     */
    public function scopeTakePage($query, $page, $perPage)
    {
        if ( ! $page || ! $perPage) {
            return $query;
        }

        $limit  = $perPage;
        $offset = ($page - 1) * $limit;

        return $query->take($limit)->skip($offset);
    }

}
