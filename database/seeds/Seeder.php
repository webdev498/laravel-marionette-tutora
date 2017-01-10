<?php

use App\User;
use App\Role;
use App\Subject;
use App\Location;
use App\Relationship;
use Illuminate\Database\Seeder as BaseSeeder;

abstract class Seeder extends BaseSeeder
{

    /**
     * Get all of the tutors
     *
     * @return Collection
     */
    protected function getTutors()
    {
        return User::whereHas('roles', function ($query) {
            return $query->where('name', '=', Role::TUTOR);
        })
        ->with('roles')
        ->get()
        ->reject(function ($user) {
            return $user->roles->first(function ($i, $role) {
                return $role->name === Role::ADMIN;
            }) ? true : false;
        });
    }

    /**
     * Get all of the students
     *
     * @return Collection
     */
    protected function getStudents()
    {
        return User::whereHas('roles', function ($query) {
            return $query->where('name', '=', Role::STUDENT);
        })
        ->with('roles')
        ->get();
    }

    /**
     * Get all of the users
     *
     * @return Collection
     */
    protected function getUsers()
    {
        $tutors   = $this->getTutors();
        $students = $this->getStudents();

        return $tutors->merge($students);
    }

    /**
     * Get every relationship
     *
     * @return Collection
     */
    protected function getRelationships()
    {
        return Relationship::all();
    }

    /**
     * Get all of the subjects
     *
     * @return Collection
     */
    protected function getSubjects()
    {
        return Subject::withDepth()
            ->having('depth', '>', 0)
            ->defaultOrder()
            ->get();
    }

    /**
     * Get all of the locations
     *
     * @return Collection
     */
    protected function getLocations()
    {
        return Location::all();
    }

}
