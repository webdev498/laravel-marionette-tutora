<?php

use App\Task;

class TasksSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Relationships
        foreach ($this->getRelationships() as $relationship) {
            $relationship->tasks()->saveMany(
                factory(Task::class, rand(2, 4))->make()->all()
            );
        }
        // Users
        foreach ($this->getUsers() as $user) {
            $user->tasks()->saveMany(
                factory(Task::class, rand(2, 4))->make()->all()
            );
        }

    }

}
