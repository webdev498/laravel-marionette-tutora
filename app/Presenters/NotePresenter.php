<?php

namespace App\Presenters;

use App\Note;
use League\Fractal\TransformerAbstract;

class NotePresenter extends TransformerAbstract
{

    /**
     * Turn this object into a generic array
     *
     * @param  Note $note
     * @return array
     */
    public function transform(Note $note)
    {
        return [
            'body' => (string) $note->body,
        ];
    }

}

