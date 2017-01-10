<?php

use App\Job;
use Faker\Factory;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $subjects       = $this->getSubjects();
        $subjectsCount  = $subjects->count();

        $locations      = $this->getLocations();
        $locationsCount = $locations->count();

        foreach ($this->getStudents() as $student) {
            $job = factory(Job::class)->make();

            $subject  = $subjects[$faker->numberBetween(0, $subjectsCount-1)];
            $location = $locations[$faker->numberBetween(0, $locationsCount-1)];

            $job->subject()->associate($subject);

            $student->jobs()->save($job);

            $job->locations()->attach($location);
        }
    }
}
