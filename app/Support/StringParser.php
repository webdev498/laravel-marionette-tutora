<?php namespace App\Support;

class StringParser
{
	
	protected $phonePattern =  '/(\d[^\d]{0,5}){10,}/i';

	protected $emailPattern = '/[\w\.]+\s?(?:@|[\[\(\{]\s?(?:@|at)\s?[\]\)\}])\s?\w+(?:(?:\s?(?:\.|[\[\(\{]\s?(?:\.|dot)\s?[\]\)\}])\s?\w+)+)?/i';

	protected $otherContactPattern = '/facebook|(?:\sFB\s)|messenger|whatsapp|whats\sapp/i';

	protected $emailEndingPattern = '/\s(hotmail|gmail|yahoo|icloud|mail|outlook|aol).?\./i';

	public function mightContainContactDetails($string) 
	{
		$stripped = $this->convertTextToNumbers($string);
		$stripped = $this->convertTextToEmailCharacters($stripped);

		if ($this->mightContainPhoneNumber($stripped)) {
			
			return true;
		}

		if ($this->mightContainEmailAddress($string)) {
			
			return true;
		}

		if ($this->mightContainOtherContactMethod($stripped)) {
			
			return true;
		}

		return false;

	}

	public function mightContainPhoneNumber($string)
	{
		$stripped = $this->convertTextToNumbers($string);

		if (preg_match($this->phonePattern, $stripped)) {
			return true;	
		}

		return false;
	}

	public function mightContainEmailAddress($string)
	{
		$stripped = $this->convertTextToEmailCharacters($string);

		if (preg_match($this->emailPattern, $string)) {

			return true;	
		}

		if (preg_match($this->emailEndingPattern, $stripped)) {	

			return true;
		}

		return false;
	}

	public function mightContainOtherContactMethod($string)
	{
		if (preg_match($this->otherContactPattern, $string)) {
			return true;	
		}

		return false;
	}

	protected function convertTextToNumbers($string)
	{
        $words = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
        $numbers = [0,1,2,3,4,5,6,7,8,9];

        $newString = str_ireplace($words, $numbers, $string);

        return $newString;
	}

	protected function convertTextToEmailCharacters($string)
	{
		$words = [' at ', ' dot '];
		
		$numbers = [' @ ', ' . '];

		$newString = str_ireplace($words, $numbers, $string);

        return $string;
	}

}