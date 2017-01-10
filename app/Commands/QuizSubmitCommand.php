<?php namespace App\Commands;

class QuizSubmitCommand extends Command
{

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        $uuid,
        $answers
    ) {
        $this->uuid     = $uuid;
        $this->answers = $answers;
    }

}
