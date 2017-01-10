<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class LocationsController extends Controller
{
    public function index()
    {
    	$locations = config('sitelinks.locations');

    	foreach ($locations as &$location)
    	{	

    		$location = array_where($location, function ($key, $value) {
			    return $value['level'] == 'top';
			});
    	}
    	return view('locations.index', compact('locations'));
    }


    public function show($region)
    {
    	$cities = config('sitelinks.locations.' . $region);

    	$count  = count($cities);

    	$break = round($count / 4);

    	return view('locations.show', compact('cities', 'break', 'region'));
    }
}