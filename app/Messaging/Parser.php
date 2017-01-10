<?php namespace App\Messaging;

use App\Tutor;
use App\Student;
use App\MessageLine;
use Illuminate\Auth\AuthManager as Auth;

class Parser
{
    const WHO_ME       = 'me';
    const WHO_YOU      = 'you';
    const WHO_SYS      = 'system';
    const SYS_START    = '>';

    /**
     * @var MessageLine
     */
    protected $line;

    /**
     * @var string
     */
    protected $who = '';

    /**
     * @var string
     */
    protected $body = '';

    /**
     * Create an instance of the parser
     *
     * @param  Auth        $auth
     * @param  MessageLine $line
     * @return void
     */
    public function __construct(Auth $auth, MessageLine $line, $addWrapper = true)
    {
        $this->line = $line;
        $this->who  = $this->guessWho($auth->user(), $line);
        $this->body = $this->parseBody($line, $auth->user(), $this->who, $addWrapper);
    }

    /**
     * Make a new instance of the parser
     *
     * @param MessageLine $line
     * @param bool|true $addWrapper
     *
     * @return static
     */
    public static function make(MessageLine $line, $addWrapper = true)
    {
        return new static(app('auth'), $line, $addWrapper);
    }

    public function getBody($addWrapper = true)
    {
        return $this->body;
    }

    public function __toString()
    {
        return $this->getBody();
    }

    public function toArray()
    {
        return array_extend($this->line->toArray(), [
            'who'  => $this->getWho(),
            'body' => $this->getBody()
        ]);
    }

    public function getWho()
    {
        return $this->who;
    }

    public function getLine()
    {
        return $this->line;
    }

    protected function guessWho($user, $line)
    {
        if ($user && $user->isAdmin()) {
            if ($line->user instanceof Tutor) {
                $who = 'me';
            } else if ($line->user instanceof Student) {
                $who = 'you';
            } else {
                $who = 'system';
            }
        } else {
            $who = 'you';

            if ($user && $line->user_id === $user->id) {
                $who = 'me';
            } else if ($line->user_id === null) {
                $who = 'system';
            }
        }
        // Return
        return $who;
    }

    protected function parseBody($line, $user, $who, $addWrapper)
    {
        $body = $line->body;

        if ($who === static::WHO_SYS && starts_with($body, static::SYS_START)) {
            // Remove the system start character
            $body = substr($body, strlen(static::SYS_START));

            list ($type, $json) = explode(' ', $body);

            $class = studly_case(strtolower($type));
            $class = sprintf('App\Messaging\%sMessage', $class);
            $data  = json_decode($json, true);
            $data  = array_to_object($data);

            if ( ! class_exists($class)) {
                $body = ucfirst(strtolower(str_replace('_', ' ', $type)));
            } else {
                $body = app($class)->present($line, $data);
            }

            return $body;
        } else {
            $body = $line->body;

            if ($user && $user->isAdmin()) {
                $body = $this->highlightFlagged($line);
            }
            
            if ($addWrapper == true) {
                return pe($body);
            } else if ($addWrapper == false) {
                return $body;
            }
        }
    }

    protected function highlightFlagged($line)
    {
        $flagger = app(Flagger::class, [$line, $line->message->relationship]);

        return $flagger->highlightFlagged();
    }

}
