<?php

namespace App\Presenters;

use App\User;

class UserPresenter extends AbstractPresenter
{

    /**
     * List of default resources to include on this presenter,
     * and every class that extends it
     *
     * @var array
     */
    protected $_defaultIncludes = [
        'private',
    ];

    /**
     * List of default resources to include
     *
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * List of resources possible to include on this presenter,
     * and every clas that extends it
     *
     * @var array
     */
    protected $_availableIncludes = [
        'addresses',
        'relationships',
        'requirements',
        'roles',
        'tasks',
        'note',
        'searches',
        'settings',
        'admin',
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * Create an instance of the presenter
     *
     * @param  Array $options
     * @return void
     */
    public function __construct(Array $options = [])
    {
        parent::__construct($options);

        $this->availableIncludes = array_merge_unique(
            $this->_availableIncludes,
            $this->availableIncludes
        );

        $this->defaultIncludes = array_merge_unique(
            $this->_defaultIncludes,
            $this->defaultIncludes
        );
    }

    /**
     * Turn this object into a generic array
     *
     * @param  User $user
     * @return array
     */
    public function transform(User $user)
    {
        $firstName = str_name($user->first_name);
        $lastName  = str_name(substr($user->last_name, 0, 1));

        return [
            'uuid'       => (string) $user->uuid,
            'status'     => (string) $user->status,
            'first_name' => (string) $firstName,
            'last_name'  => (string) $lastName,
            'name'       => (string) "{$firstName} {$lastName}",
            'created_at' => $this->formatDate($user->created_at),
            'updated_at' => $this->formatDate($user->updated_at),
        ];
    }

    /**
     * Include private data
     *
     * @param  User $user
     * @return Item
     */
    protected function includePrivate(User $user)
    {
        return $this->item($user, function ($user) {
            $firstName = str_name($user->first_name);
            $lastName  = str_name($user->last_name);
            $dob       = $this->formatDob($user->dob);

            return [
                'last_name'  => (string)  $lastName,
                'name'       => (string)  "$firstName $lastName",
                'email'      => (string)  $user->email,
                'telephone'  => (string)  $user->telephone,
                'last_four'  => (integer) $user->last_four,
                'billing_id' => $user->billing_id,
                'dob'       =>  $dob,
                'deleted_at' => $user->deleted_at ? $this->formatDate($user->deleted_at) : null,
                'blocked_at' => $user->blocked_at ? : null
            ];
        });
    }


     /**
     * Include admin data
     *
     * @param  User $user
     * @return Item
     */
    protected function includeAdmin(User $user)
    {
        return $this->item($user, function ($user) {
            
            return [
                'subscription_token' => $user->subscription->generateToken(),
                'transgressions'=> (integer) $user->transgressions->count(),
            ];
        });
    }   

    /**
     * Include addresses data
     *
     * @param  User $user
     * @return Item
     */
    protected function includeAddresses(User $user)
    {
        return $this->item($user->addresses, new AddressesPresenter());
    }

    /**
     * Include relationship data
     *
     * @param  User $user
     * @return Collection
     */
    protected function includeRelationships(User $user)
    {
        return $this->collection(
            $user->relationships,
            new RelationshipPresenter()
        );
    }

    /**
     * Include the requirements data
     *
     * @param  User $user
     * @return Collection
     */
    protected function includeRequirements(User $user)
    {
        return $this->item(
            $user->requirements,
            new UserRequirementsPresenter()
        );
    }

    /**
     * Include the role data
     *
     * @param  User $user
     * @return Collection
     */
    protected function includeRoles(User $user)
    {
        return $this->collection(
            $user->roles,
            new UserRolesPresenter()
        );
    }


    /**
     * Include the tasks
     *
     * @param  User $user
     * @return Collection
     */
    protected function includeTasks(User $user)
    {
        return $this->collection($user->tasks, new TaskPresenter());
    }

    /**
     * Include the note
     *
     * @param  User $user
     * @return Collection
     */
    protected function includeNote(User $user)
    {
        $note = $user->note ?: new \App\Note();
        return $this->item($note, new NotePresenter());
    }

    /**
     * Include the searches
     *
     * @param  User $user
     * @return Collection
     */
    protected function includeSearches(User $user)
    {
        return $this->collection($user->searches, new SearchesPresenter());
    } 

    /**
     * Format the users DOB
     *
     * @param  mixed
     * @return mixed
     */
    protected function formatDob($dob)
    {
        return $dob ? $dob->format('d/m/Y') : null;
    }
}
