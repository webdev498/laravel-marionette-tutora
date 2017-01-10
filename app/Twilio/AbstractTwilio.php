<?php namespace App\Twilio;

use App\User;
use Services_Twilio as Twilio;
use Illuminate\Queue\QueueManager as Queue;
use App\Presenters\PresenterTrait;

abstract class AbstractTwilio
{

    use PresenterTrait;

    /**
     * @var Twilio
     */
    protected $twilio;


    /**
     * @var Twilio
     */
    protected $queue;
    /**
     * Create an instance of Twilio
     *
     * @param  Mail $mail
     * @return void
     */
    public function __construct(Queue $queue)
    {
        $this->queue = $queue;

        $this->twilio = new Twilio(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }


    /**
     * Send a text to the given user
     *
     * @param  User   $user
     * @param  string $message
     * @param  array  $data
     * @return void
     */
    public function sendToUser(User $user, $message, $list = null)
    {
        if ($user->deleted_at !== null) {
            return;
        }

        if ($user->blocked_at !== null) {
            return;
        }

        if ($list !== null && ! $user->subscription->isSubscribed($list)) {
            return;
        }

        $to = $user->telephone;

        if ( ! $user->text_alerts || ! preg_match('/^(?:0[7]|\+44)/', $to)) {
            return;
        }

        if ( ! starts_with($to, '+44')) {
            $to = '+44'.substr($to, 1);
        }
        $twilio = $this->twilio;

        $this->queue->push(function($job) use ($twilio, $to, $message)
        {
            try {
                $twilio->account->messages->create([
                    'From' => config('services.twilio.from'),
                    'To'   => $to,
                    'Body' => $message,
                ]);
            
            } catch (\Exception $e) {
                \Log::error('[ Twilio ] '.$e->getMessage());
            }
            $job->delete();    
        });

        
    }

}