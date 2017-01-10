<?php

use App\User;
use App\Relationship;

class RelationshipSeeder extends Seeder
{
    /**
     * @var array
     */
    protected $relationships = [
        'aaron.lord@tutor.com' => [
            'melissa.lord@student.com' => [
                'is_confirmed' => true,
                'status'       => 'pending',
            ],
            'scott.woodley@student.com' => [
                'is_confirmed' => false,
                'status'       => 'pending',
            ],
        ],
        'mark.hughes@tutor.com' => [
            'scott.woodley@student.com' => [
                'is_confirmed' => true,
                'status'       => 'pending',
            ],
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Lookups
        $tutors   = $this->getTutors();
        $students = $this->getStudents();
        // Manual
        foreach ($this->relationships as $tmail => $s) {
            $tutor = $tutors->first(function ($key, $tutor) use ($tmail) {
                return $tutor->email === $tmail;
            });

            foreach ($s as $smail => $attributes) {
                $student = $students->first(function ($key, $student) use ($smail) {
                    return $student->email === $smail;
                });

                $attributes['tutor_id']   = $tutor->id;
                $attributes['student_id'] = $student->id;

                factory(Relationship::class)
                    ->create($attributes);
            }
        }
        // Randoms
        $ignore = array_keys($this->relationships);
        foreach ($tutors as $tutor) {
            if (in_array($tutor->email, $ignore)) {
                continue;
            }

            foreach ($students->random(2) as $student) {
                factory(Relationship::class)
                    ->create([
                        'tutor_id'   => $tutor->id,
                        'student_id' => $student->id,
                    ]);
            }
        }
    }
}
