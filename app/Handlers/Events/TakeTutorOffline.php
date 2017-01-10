<?php namespace App\Handlers\Events;

use App\User;
use App\UserProfile;
use App\Commands\UpdateUserCommand;
use App\Events\TutorNoResponseLimitReached;
use App\Repositories\Contracts\UserProfileRepositoryInterface;
use Illuminate\Foundation\Bus\DispatchesCommands;

class TakeTutorOffline extends EventHandler
{

	use DispatchesCommands;
	
	/**
     * @var UserProfile $profiles
     */
    protected $profiles;

    /**
     * Create the event handler.
     *
     * @param  UserProfileRepositoryInterface $profiles
     * @return void
     */
    public function __construct(
        UserProfileRepositoryInterface $profiles
    ) {
        $this->profiles   = $profiles;
    }

    /**
     * Handle the event.
     *
     * @param  TutorNoResponseLimitReached $event
     * @return void
     */
    public function handle(TutorNoResponseLimitReached $event)
    {
    	$tutor = $event->tutor;
    	$profile = [];
    	$profile['status'] = UserProfile::OFFLINE;

    	$data = [];
    	$data['uuid']      = $tutor->uuid;
    	$data['profile'] = $profile;

    	$profile = $this->dispatchFromArray(UpdateUserCommand::class, $data);
    }
}