<?php namespace App;

use App\Relationship;

class Enquiry extends Relationship
{
 
    /**
     * Override the parent newQuery class to only show relationships that were from a job application
     * 
     * @return Builder $query
     */
    public function newQuery()
    {
        $query = parent::newQuery();

        $query->where('is_application','=', false);
    
        return $query;
    }

}