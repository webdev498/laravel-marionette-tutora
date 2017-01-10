<?php namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'private',
    ];

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
        return $this->item($user, function (User $user) {
            $firstName = str_name($user->first_name);
            $lastName  = str_name($user->last_name);

            return [
                'last_name'  => (string) $lastName,
                'name'       => (string) "{$firstName} {$lastName}",
                'rate'      =>  $user->profile ? $user->profile->rate : null
            ];
        });
    }

}
