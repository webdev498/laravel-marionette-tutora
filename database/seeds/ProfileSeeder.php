<?php

use App\UserProfile;
use App\UserRequirement;
use App\UserBackgroundCheck;
use App\UserQualificationOther;
use App\UserQualificationAlevel;
use App\UserQualificationUniversity;
use App\UserQualificationTeacherStatus;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = $this->getSubjects();

        foreach ($this->getTutors() as $tutor) {
            // User profile
            $tutor->profile()->save(
                factory(UserProfile::class)->make(
                    $tutor->uuid === 'aaron.lord'
                        ? ['rate' => '25']
                        : []
                )
            );
            // Subjects
            $tutor->subjects()->attach(array_merge(
                [2], // Maths
                $subjects->random(2)->lists('id')->toArray()
            ));
            // qualifications
            $tutor->qualificationUniversities()->saveMany(
                factory(UserQualificationUniversity::class, rand(2, 4))->make()
            );
            $tutor->qualificationAlevels()->saveMany(
                factory(UserQualificationAlevel::class, rand(2, 4))->make()
            );
            $tutor->qualificationOthers()->saveMany(
                factory(UserQualificationOther::class, rand(2, 4))->make()
            );
            // qts
            $tutor->qualificationTeacherStatus()->save(
                factory(UserQualificationTeacherStatus::class)->make()
            );
            // background_checks
            $tutor->backgroundCheck()->save(
                factory(UserBackgroundCheck::class)->make()
            );
        }
    }
}
