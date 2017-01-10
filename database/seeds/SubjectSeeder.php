<?php

use App\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = config('seeding.subjects');

        foreach (config('seeding.subjects') as $subject) {
            Subject::create($subject);
        }
    }
}
