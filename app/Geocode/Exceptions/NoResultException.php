<?php namespace App\Geocode\Exceptions;

class NoResultException extends GeocodeException
{

    protected $message = 'ERR_GEOCODE_NO_RESULT';

}
