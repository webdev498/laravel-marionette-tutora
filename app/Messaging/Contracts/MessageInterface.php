<?php namespace App\Messaging\Contracts;

use App\MessageLine;

interface MessageInterface
{

    /**
     * Present the message line
     *
     * @param  MessageLine $line
     * @param  Array       $data
     * @return String
     */
    public function present(MessageLine $line, $data);

}
