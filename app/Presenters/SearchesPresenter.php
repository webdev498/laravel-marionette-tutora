<?php

namespace App\Presenters;

use App\Search;
use League\Fractal\TransformerAbstract;

class SearchesPresenter extends TransformerAbstract
{


    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'subject',
    ];

 
    /**
     * Turn this object into a generic array
     *
     * @param  Task $task
     * @return array
     */
    public function transform(Search $search)
    {
        return [
            'id'         => (integer) $search->id,
            'subject'    => (string) $search->subject,
            'street'     => (string) $search->street,
            'city'       => (string) $search->city,
            'county'     => (string) $search->city,
            'postcode'   => (string) $search->postcode,
            'location'   => (string) $this->location($search->postcode, $search->street, $search->city),
        ];
    }


    /**
     * Include subject data
     *
     * @param  Search $search
     * @return Item
     */
    public function includeSubject(Search $search)
    {
        $subject = $search->subject;
        if ($subject) {
            return $this->item($search->subject, new SubjectPresenter());
        }
    }

     /**
     * Return the best location using postcode, street and city
     *
     * @param  Search $search
     * @return Item
     */
    public function location($postcode, $street, $city)
    {
        if ($location = $postcode) {
            return $location;
        }

        if ($street && $city) {
            return $location = $street . ', ' . $city;
        }

        if ($city) {
            return $location = $city;
        }

        return null;
    }   

}

