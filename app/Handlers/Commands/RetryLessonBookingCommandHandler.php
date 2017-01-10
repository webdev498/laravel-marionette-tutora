<?php namespace App\Handlers\Commands;

use App\LessonBooking;
use Illuminate\Database\DatabaseManager;
use App\Commands\RetryLessonBookingCommand;
use App\Repositories\Contracts\LessonBookingRepositoryInterface;

class RetryLessonBookingCommandHandler extends CommandHandler
{
	
	/**
	 * @var LessonBookingRepositoryInterface
	 */
	protected $bookings;

	/**
     * @var DatabaseManager
     */
	protected $database;


	public function __construct(LessonBookingRepositoryInterface $bookings, DatabaseManager $database)
	{
		$this->bookings = $bookings;
		$this->database = $database;
	}

    /**
     * Handle the command.
     *
     * @param  CancelLessonBookingCommand  $command
     * @return void
     */
    public function handle(RetryLessonBookingCommand $command)
    {
    	
    	
    	return $this->database->transaction(function () use ($command) {
    		
    		// Lookups
    		$booking = $this->bookings->findByUuid($command->uuid);
            // Reauthorise
	        $booking = LessonBooking::reauthorise($booking);
	        // Dispatch
	        $this->dispatchFor($booking);
	        // Save
	        $this->bookings->save($booking);
    	});
    }
}