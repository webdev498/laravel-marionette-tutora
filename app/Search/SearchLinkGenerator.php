<?php namespace App\Search;

class SearchLinkGenerator 
{


	protected $location = null;

	protected $subject = null;

	protected $siteLinkLocations;

	public function __construct()
	{
		$this->siteLinkLocations = config('sitelinks.locations');
		$this->siteLinkSubjects = config('subjects.full_subjects');
 	}

	public function setLocation($location)
	{
		$this->location = strtolower($location);
	}

	public function setSubject($subject)
	{
		$this->subject = strtolower($subject);
	}


	public function isSearchALandingPage()
	{
		// Have a subject and a location
		if ($this->subject && $this->location) {
			$subjects = $this->getSubjectsForLocation();
			
			if (is_array($subjects) && array_key_exists($this->subject, $subjects)) {
				return true;
			}

			return false;

		}

		// Only a location
		if (! $this->subject && $this->location) {

			if ($this->locationExists()) {
				return true;
			} 

			return false;
		}

		// Only a subject
		if ($this->subject && ! $this->location) {
			if ($this->subjectExists()) {
				return true;
			}

			return false;
		}

		return false;


	}

	public function generateSubjects()
	{
		return $this->getSubjectsForLocation();
	}

	public function generateLocations()
	{
		$locations = [];

		if (! $this->location && $this->subject) {

			return $this->getLocationsForSubject();

		}

		if ($this->location && $this->subject) {

			foreach($this->siteLinkLocations as $region)
			{
				foreach($region as $city => $useExtendedSubjects)
				{
					if($city == $this->location)
					{
						$cityRegion = $region;
					}
				}
			}

			if (!isset($cityRegion)) {
				$locations = null;

			} else {

				foreach($this->siteLinkLocations as $region)
				{
					if ($region == $cityRegion)
					{
						$locations = [];
						foreach ($region as $location => $value)
						{
							if($value['level'] == 'top')
								$locations[] = $location;
						}
						
					}
				}
			}
			



		}

		return $locations;
	}


	protected function locationExists() 
	{
		foreach($this->siteLinkLocations as $region)
		{
			foreach($region as $city => $value)
			{
				
				if($city == $this->location) {
					
					return true;
				}
			}
		}

		return null;
	}

	protected function getSubjectsForLocation() 
	{
		foreach($this->siteLinkLocations as $region)
		{
			foreach($region as $city => $value)
			{
				if($city == $this->location) {
					
					$subjects = $value['subjects'];					
					$subjects =  $subjects ? config('sitelinks.' . $subjects) : null;
					return $subjects;
				}
			}
		}

		return null;
	}

	protected function getLocationsForSubject()
	{
		foreach($this->siteLinkSubjects as $subjectGroup)
		{
			foreach($subjectGroup as $subject => $value)
			{
				if($subject == $this->subject)
				{
					
					$locations = $value['cities'];
			
					$locations =  $locations ? config('subjects.cities.' . $locations) : null;
					return $locations;
				}
			}
		}

		return null;
	}

	protected function subjectExists()
	{
		foreach($this->siteLinkSubjects as $subjectGroup)
		{
			foreach($subjectGroup as $subject => $value)
			{
				if($subject == $this->subject)
				{
					
					return true;
				}
			}
		}

		return null;
	}
}