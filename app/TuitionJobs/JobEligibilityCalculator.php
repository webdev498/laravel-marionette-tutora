<?php namespace App\TuitionJobs;

use App\Address;
use App\Tutor;
use App\UserProfile;

class JobEligibilityCalculator
{
	public function isEligible(Tutor $tutor)
	{

		if (! $this->isLive($tutor->profile)) return false;

		if (! $this->scoreIsEligible($tutor->profile)) return false;

		if (! $this->hasLocation($tutor)) return false;

		if (! $this->locationIsEligible($tutor)) return false;

		if ( $this->isDeleted($tutor)) return false;

		return true;


	}

	protected function isLive(UserProfile $profile)
	{
		$isLive = $profile->admin_status == UserProfile::OK && $profile->status == UserProfile::LIVE;

		if ($isLive) return true;

		return false;
	}

	protected function scoreIsEligible(UserProfile $profile)
	{
		$scoreIsEligible = $this->profileScoreIsEligible($profile) &&  $profile->booking_score > config('algorithm.minBookingScore');

		if ($scoreIsEligible) return true;

		return false;
	}

	/**
	 * @param UserProfile $profile
	 *
	 * @return bool
	 */
	public function profileScoreIsEligible(UserProfile $profile)
	{
		$scoreIsEligible = $profile->profile_score > config('algorithm.minProfileScore');

		return $scoreIsEligible;
	}


	protected function hasLocation(Tutor $tutor)
	{
		$hasAddress  = $tutor->addresses()->where('name', Address::NORMAL)->whereNotNull('latitude')->count() > 0;

		if ($hasAddress) return true;

		return false;
	}

	protected function locationIsEligible(Tutor $tutor)
	{
		$address = $tutor->addresses()->where('name', Address::NORMAL)->first();
		$city = $address->city;

		dump($city);

		if ($city == 'London' || $city == 'Wembley') {
			$profile = $tutor->profile;
			if ($profile->booking_score > 1.1) {
				return true;
			} else {
				return false;
			}
		}

		return true;
	}

	protected function isDeleted(Tutor $tutor)
	{
		if ($tutor->deleted_at) return true;

		return false;
	}
}