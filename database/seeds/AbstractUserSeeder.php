<?php

use App\User;
use App\Role;
use App\Admin;
use App\Tutor;
use App\Student;
use Illuminate\Support\Collection;

abstract class AbstractUserSeeder extends Seeder
{
    const ROLES = [
        'admin'   => Admin::class,
        'tutor'   => Tutor::class,
        'student' => Student::class,
    ];

    /**
     * Find the roles by given names
     *
     * @param  string $name
     * @param  ...
     * @return array
     */
    protected function findRoles()
    {
        return Role::whereIn('name', func_get_args())->get();
    }

    /**
     * Create a user
     *
     * @param  Array      $attributes
     * @param  Collection $roles
     * @return void
     */
    protected function createUser(Array $attributes = [], Collection $roles)
    {
        $user = factory(User::class, static::ROLES[$roles->first()->name])
            ->create($attributes);

        $user->roles()->attach(
            $roles->lists('id')->toArray()
        );
    }

    /**
     * Create an many administrators
     *
     * @param  integer    $count
     * @param  Array      $attributes
     * @param  Collection $roles
     * @return void
     */
    protected function createUsers($count, Array $attributes = [], Collection $roles)
    {
        return factory(User::class, static::ROLES[$roles->first()->name], $count)
            ->create($attributes)
            ->each(function ($user) use ($roles) {
                $user->roles()->attach(
                    $roles->lists('id')->toArray()
                );
            });

    }

}
