<?php namespace App\Handlers\Events;

use App\Tutor;
use App\Events\UserWasEdited;
use Illuminate\Queue\QueueManager as Queue;
use App\Billing\Contracts\BillingInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class UpdateUserBillingAccount extends EventHandler
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
     * @param  UserWasEdited $event
     * @return void
     */
    public function handle(UserWasEdited $event)
    {
        $id = $event->user->id;

        $this->queue->push(function ($job) use ($id) {
            $users   = app(UserRepositoryInterface::class);
            $billing = app(BillingInterface::class);

            $user = $users->findById($id);

            $account = $billing->account($user);
            $account->sync();

            $job->delete();
        });
    }

}
