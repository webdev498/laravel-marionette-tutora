<?php namespace App\Commands;

use App\Commands\Command;

class RefundLessonBookingCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        $uuid,
        $amount,
        $reverse_transfer
    ) {
        $this->uuid    = $uuid;
        $this->amount  = $amount;
        $this->reverse_transfer = $reverse_transfer;
    }

}
