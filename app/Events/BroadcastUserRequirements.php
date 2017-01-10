<?php

namespace App\Events;

use App\Tutor;
use App\Events\Event;
use App\UserRequirement;
use App\UserRequirementCollection;
use App\Transformers\TransformerTrait;
use Illuminate\Queue\SerializesModels;
use App\Transformers\UserRequirementTransformer;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BroadcastUserRequirements extends Event implements ShouldBroadcast
{

    use SerializesModels, TransformerTrait;

    /**
     * @var Tutor
     */
    protected $tutor;

    /**
     * @var UserRequirementCollection
     */
    public $requirements;

    /**
     * Create a new event instance.
     *
     * @param  Tutor $tutor
     * @return void
     */
    public function __construct(Tutor $tutor)
    {
        $this->tutor        = $tutor;
        $this->requirements = $this->transformCollection(
            $tutor->requirements,
            new UserRequirementTransformer()
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
        return 'user_requirements';
    }
}
