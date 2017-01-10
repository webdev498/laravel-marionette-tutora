<?php namespace App\Handlers\Events;

use App\Tutor;
use App\IdentityDocument;
use App\Events\UserWasLegalEdited;
use Illuminate\Queue\QueueManager as Queue;
use App\Billing\Contracts\BillingInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class VerifyUserBillingAccount extends EventHandler
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
     * @param  UserWasLegalEdited $event
     * @return void
     */
    public function handle(UserWasLegalEdited $event)
    {
        $id = $event->user->id;

        $this->queue->push(function ($job) use ($id) {
            $users   = app(UserRepositoryInterface::class);
            $billing = app(BillingInterface::class);

            // Lookup
            $tutor = $users->findById($id);
            if ($tutor) {
                // Lookup
                $identityDocument = $tutor->identityDocument;
                if ($identityDocument) {
                    // Billing
                    $account = $billing->account($tutor);
                    $file    = $account->identityDocument($identityDocument);
                    // Update
                    IdentityDocument::sent($identityDocument, $file);
                    // Save
                    $users->save($tutor);
                    // Dispatch
                    event(head($identityDocument->releaseEvents()));
                }
            }

            $job->delete();
        });
    }

}
