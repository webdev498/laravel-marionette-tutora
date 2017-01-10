<?php namespace App\Transformers;

use App\Tutor;
use App\User;
use League\Fractal\TransformerAbstract;

class TutorStudentsTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'private'
    ];

    /**
     * @var
     */
    protected $tutor;

    /**
     * @param Tutor $tutor
     */
    public function __construct(Tutor $tutor)
    {
        $this->tutor = $tutor;
        $this->defaultIncludes = ['private'];
    }

    /**
     * Turn this object into a generic array
     *
     * @return array
     */
    public function transform(User $user)
    {
        $firstName = str_name($user->first_name);
        $lastName  = str_name(substr($user->last_name, 0, 1));

        return [
            'uuid'       => (string) $user->uuid,
            'first_name' => (string) $firstName,
            'last_name'  => (string) $lastName,
            'name'       => (string) "{$firstName} {$lastName}",
        ];
    }

    protected function includePrivate(User $user)
    {
        $relationships = app()->make('App\Repositories\Contracts\RelationshipRepositoryInterface');
        $relationship = $relationships->findByTutorAndStudent($this->tutor, $user);

        return $this->item($user, function (User $user) use ($relationship) {
            $firstName = str_name($user->first_name);
            $lastName  = str_name($user->last_name);

            return [
                'last_name'  => (string) $lastName,
                'name'       => (string) "{$firstName} {$lastName}",
                'rate'       => $relationship->rate
            ];
        });
    }

}
