<?php

namespace App\Events\Broadcasts;

use App\Tutor;
use App\Events\Event;
use App\UserRequirement;
use Illuminate\Database\Eloquent\Collection;
use App\Transformers\TransformerTrait;
use Illuminate\Queue\SerializesModels;
use App\Transformers\TutorBackgroundChecksTransformer;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BroadcastTutorBackgroundChecks extends Event implements ShouldBroadcast
{

    use SerializesModels, TransformerTrait;

    /**
     * @var Tutor
     */
    protected $tutor;

    /**
     * @var Collection
     */
    public $background_checks;

    /**
     * Create a new event instance.
     *
     * @param  Tutor $tutor
     */
    public function __construct(Tutor $tutor)
    {
        $this->tutor        = $tutor;
        $this->background_checks = $this->transformItem(
            $tutor,
            new TutorBackgroundChecksTransformer()
        );
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['user.'.$this->tutor->uuid];
    }

    /**
    * Get the broadcast event name.
    *
    * @return array
    */
    public function broadcastAs()
    {
        return 'user_background_checks';
    }
}
