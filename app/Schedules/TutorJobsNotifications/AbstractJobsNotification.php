<?php 

namespace App\Schedules\TutorJobsNotifications;

use App\TuitionJobs\JobEligibilityCalculator;
use App\Mailers\TutorJobsMailer;
use App\Repositories\Contracts\JobRepositoryInterface;
use App\Search\JobSearcher;
use App\Tutor;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;

abstract class AbstractJobsNotification
{
	
	use DispatchesJobs;

	/**
	 * @var TutorJobsMailer
	 */
	protected $mailer;

	/**
	 * @var Tutor
	 */
	protected $tutor;

	/**
	 * @var JobRepositoryInterface
	 */
	protected $jobs;

	/**
	 * @var JobSearcher
	 */
	protected $searcher;
	
	/**
	 * @var JobEligibilityCalculator
	 */
	protected $calc;


	public function __construct(
		TutorJobsMailer 	   $mailer,
		JobRepositoryInterface $jobs,
        JobSearcher   		   $searcher,
		JobEligibilityCalculator $calc
	)
	{
		$this->mailer 	= $mailer;
		$this->jobs	  	= $jobs;
		$this->searcher	= $searcher;
		$this->calc = $calc;
	}
	
	public function setProperties(Tutor $tutor)
	{
		$this->tutor 	= $tutor;
		$this->schedule = $tutor->schedule;
	}

	protected function instantiateNotification($notificationClassName)
	{
		$notificationClassName = __NAMESPACE__ . "\\". $notificationClassName;

		$notification = app($notificationClassName);
		return $notification;
	}

	/**
	 * @return Carbon
	 */
	protected function getNow()
	{
		return Carbon::now();
	}

}