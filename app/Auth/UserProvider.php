<?php namespace App\Auth;

use App\Tutor;
use App\Student;
use Illuminate\Auth\EloquentUserProvider;

class UserProvider extends EloquentUserProvider
{

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        $model = parent::retrieveById($identifier);
        return $this->changeModel($model);
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        $model = parent::retrieveByToken($identifier, $token);
        return $this->changeModel($model);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $model = parent::retrieveByCredentials($credentials);
        return $this->changeModel($model);
    }

    /**
     * Update a model to a Student or Tutor class based on their role.
     *
     * @param  User $model
     * @return Student|Tutor
     */
    protected function changeModel($model)
    {
        return $model;
    }

}
