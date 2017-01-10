<?php namespace App\Handlers\Events;

use App\Tutor;
use App\Events\UserWasRegistered;
use App\Billing\Contracts\BillingInterface;
use Illuminate\Queue\QueueManager as Queue;
use App\Repositories\Contracts\UserRepositoryInterface;

class CreateUserBillingAccount extends EventHandler
{

    /**
     * @var Queue
     */
    protected $queue;

    /**
     * Create the event handler.
     *
     * @param  Queue $queue
     * @return void
     */
    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    /**
     * Handle the event.
     *
     * @param  UserWasRegistered  $event
     * @return void
     */
    public function handle(UserWasRegistered $event)
    {
        $id = $event->user->id;

        $this->queue->push(function ($job) use ($id) {
            $users   = app(UserRepositoryInterface::class);
            $billing = app(BillingInterface::class);

            $user = $users->findById($id);

            $billing->account($user);

            $job->delete();
        });
    }

}
