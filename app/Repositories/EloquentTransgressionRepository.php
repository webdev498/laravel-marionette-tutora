<?php namespace App\Repositories;

use App\Transgression;
use App\User;
use App\Message;
use App\Repositories\Contracts\TransgressionRepositoryInterface;

class EloquentTransgressionRepository extends AbstractEloquentRepository implements
    TransgressionRepositoryInterface
{
	/*
     * @var Transgression
     */
    protected $transgression;

    /**
     * Create an instance of this
     *
     * @param  Database $databse
     * @param  LessonBooking $booking
     * @return void
     */
    public function __construct(Transgression $transgression)
    {
        $this->transgression = $transgression;
    }

    public function saveForMessage($transgression, Message $message)
    {
    	$message->transgressions()->save($transgression);

        return $transgression;
    }

    public function getPage($page, $perPage)
    {
        return $this->transgression
            ->with($this->with)
            ->takePage($page, $perPage)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function count()
    {
        return $this->transgression                      
            ->count();
    }


}