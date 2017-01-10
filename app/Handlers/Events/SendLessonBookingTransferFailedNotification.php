<?php

namespace App\Handlers\Events;

use App\Events\LessonBookingTransferFailed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendLessonBookingTransferFailedNotification
{
    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  LessonBookingTransferFailed  $event
     * @return void
     */
    public function handle(LessonBookingTransferFailed $event)
    {
        $booking = $event->booking;
        loginfo ("TransferFailed");

    }
}
