<?php namespace App\Repositories;

use App\UserProfile;
use App\UserProfileTask;
use App\Repositories\Contracts\UserProfileTaskRepositoryInterface;

class EloquentUserProfileTaskRepository implements UserProfileTaskRepositoryInterface
{

    protected $task;

    public function __construct(UserProfileTask $task)
    {
        $this->task = $task;
    }

    public function saveForProfile(UserProfile $profile, UserProfileTask $task)
    {
        if ( ! $profile->tasks()->save($task)) {
            throw new \Exception('Failed to save user profile resource');
        }

        return $task;
    }

    public function saveManyForProfile(UserProfile $profile, Array $tasks)
    {
        if ( ! $profile->tasks()->saveMany($tasks)) {
            throw new \Exception('Failed to save user profile resource');
        }

        return $tasks;
    }

    public function deleteTasks(UserProfile $profile, $tasks)
    {
        return $profile->tasks()
            ->whereIn('name', $tasks)
            ->delete();
    }

}
