<?php 

namespace App\Students;

use App\Job;
use App\JobApplication;
use App\Relationship;
use App\Student;
use Carbon\Carbon;

class StudentStatusCalculator
{
	/**
	 * @var Student $student
	 */
	protected $student;

    /**
     * @var Collection $recentJobs
     */
    protected $recentJobs;

	/**
	 * @var Collection $recentRelationships
	 */
	protected $recentRelationships;

	/**
	 * Construct
	 * @var Student $student
	 * @param 
	 */
	public function __construct(Student $student)
	{
		$this->student 				= $student;
		$this->recentRelationships	= $this->getRecentRelationships();
		$this->recentJobs 			= $this->getRecentJobs();
	}

    public function isNotReplied()
    {
        // Case Not Replied
        if ($this->isMismatched() && $this->hasNotRepliedResponses()) return true;
    }

    public function hasPositiveResponse()
    {
        if (count($this->recentRelationships) == 0) return false;

        foreach ($this->recentRelationships as $relationship)
        {
            if ($relationship->status == Relationship::CHATTING || $relationship->status == Relationship::CONFIRMED || $relationship->status == Relationship::NO_REPLY_STUDENT) {
                return true;
            }
            return false;

        }

    }

	
    public function isMismatched()
    {

        foreach ($this->recentRelationships as $relationship)
        {
            loginfo($relationship);
            if ($relationship->status == Relationship::CHATTING || $relationship->is_confirmed == true || $relationship->status == Relationship::NO_REPLY_STUDENT || $relationship->status == Relationship::PENDING) {
                return false;
            }
        } 

        return true;
    }

    public function hasNotRepliedResponses()
    {
    	foreach ($this->recentRelationships as $relationship)
    	{
    		if ($relationship->status == Relationship::NO_REPLY_STUDENT) return true;
    	}
    	return false;
    }

    public function hasJobApplications()
    {
        foreach ($this->recentRelationships as $relationship)
        {
            if ($relationship instanceof JobApplication) return true;
        }
        return false;
    }

    public function hasJob()
    {
    	return $this->recentJobs->count() ? true : false;
    }


    /**
     * Set Student relationships
     */
    protected function getRecentRelationships()
    {
    	return $this->student->relationships()->recent()->get();
    
    }

    /**
     * Set Student JobApplications
     */
    protected function getRecentJobApplications()
    {
        return $this->student->enquiries()->recent()->get();
    
    }

    /**
     * Set Student Jobs 
     */
    protected function getRecentJobs()
    {
    	return $this->student->jobs()->recent()->wasLive()->get();
    }


}