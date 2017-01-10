<?php

use App\UserRequirement;

class RequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getTutors() as $tutor) {
            $tutor->requirements()->saveMany(
                array_map(function ($attributes) {
                    return factory(UserRequirement::class)->make(
                        array_extend(['is_completed' => true], $attributes)
                    );
                }, config('requirements'))
            );
        }
    }
}
