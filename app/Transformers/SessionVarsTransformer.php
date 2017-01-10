<?php namespace App\Transformers;

use App\Subject;
use League\Fractal\TransformerAbstract;
use Illuminate\Session\SessionManager as Session;

class SessionVarsTransformer extends TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'query',
    ];

    /**
     * @var Session
     */
    protected $session;

    /**
     * Turn this object into a generic array
     *
     * @param  Session $session
     *
     * @return array
     */
    public function transform(Session $session)
    {
        $this->session = $session;

        return [];
    }

    protected function includeQuery(Session $session)
    {
        return $this->item($session, function ($session) {
            $subject = $session->get('subject');
            $location = $session->get('location');

            if($subject && !is_object($subject)) {
                $subject = Subject::find($subject);
            }

            return [
                'subject'   => $subject ? $subject->title : '',
                'location'  => $location ? $location->postcode : '',
            ];
        });
    }
}
