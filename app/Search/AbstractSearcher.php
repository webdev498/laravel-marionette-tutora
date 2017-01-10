<?php namespace App\Search;

abstract class AbstractSearcher
{

    /**
     * Extract the search terms from a query
     *
     * @param  String $query
     * @return Array
     */
    protected function extractTerms($query)
    {
        $query = str_replace('-', ' ', $query);
        $parts = explode(' ', $query);
        $parts = array_map('str_normalize', $parts);
        $parts = array_unique($parts);

        return $parts;
    }

}
