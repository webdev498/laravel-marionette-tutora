<?php namespace App;

use App\Database\Model;
use App\Geocode\Location;
use App\Subject;


class Search extends Model
{

    
    /**
     * @param  Location|null
     * @param  Subject|null
     * @return App\Search
     */
    public static function make(Location $location = null, Subject $subject = null)
    {
        $search = new static();

        if ($location !== null) {
            $search->street = $location->street;
            $search->city = $location->city;
            $search->county = $location->county;
            $search->country = $location->country;
            $search->postcode = $location->postcode;
            $search->latitude = $location->latitude;
            $search->longitude = $location->longitude;
        }

        if ($subject !== null) {
            $search->subject_id = $subject->id;    
        }
        
        return $search;   
    }

    /**
     * A Search has one subject
     *
     * @return HasOne
     */
    public function subject()
    {
        return $this->belongsTo('App\Subject');
    }

    /**
     * Get all of the relationships that are assigned this Search.
     */
    public function relationships()
    {
        return $this->morphedByMany('App\Relationship', 'searchable')->withTimestamps();
    }

    /**
     * Get all of the students that are assigned this Search.
     */
    public function students()
    {
        return $this->morphedByMany('App\Student', 'searchable')->withTimestamps();
    }

}
