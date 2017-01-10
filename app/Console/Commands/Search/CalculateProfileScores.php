<?php 

namespace App\Console\Commands\Search;

use App\Console\Commands\Command;
use App\Repositories\Contracts\TutorRepositoryInterface;
use App\Repositories\Contracts\UserProfileRepositoryInterface;
use App\Search\Algorithm\TutorProfileScorer;
use App\Tutor;


class CalculateProfileScores extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tutora:calculate_profile_scores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate the profile score for every tutor.';

    protected $tutors;

    protected $scorer;

    protected $profiles;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        TutorRepositoryInterface $tutors,
        TutorProfileScorer $scorer,
        UserProfileRepositoryInterface $profiles
    ) {
        parent::__construct();
		$this->tutors = $tutors;
        $this->scorer = $scorer;
        $this->profiles = $profiles;
    }
    
	/**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
    	$acceptedTutors = Tutor::whereHas('profile', function ($query) {
            $query->where('admin_status', '=', 'ok');
            })
            ->chunk(200, function($tutors) {
                foreach ($tutors as $tutor) {
                    $this->scorer->setTutor($tutor);
                    $score = $this->scorer->calculateProfileScore();
                    $bookingScore = $this->scorer->calculateBookingScore();

                    $profile = $tutor->profile;
                    $profile->profile_score = $score;
                    $profile->booking_score = $bookingScore;
                    $this->profiles->save($profile);   
                }
            });
        }
    }