<?php

namespace App\Commands;

class FormRelationshipCommand extends Command
{
    /**
     * Create a new command instance.
     *
     *
     * @param  mixed $tutor
     * @param  mixed $student
     * @return void
     */
    public function __construct($tutor, $student, $search = null)
    {
        $this->tutor            = $tutor;
        $this->student          = $student;
        $this->search           = $search;
    }
}
