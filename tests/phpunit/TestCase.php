<?php

use App\User;
use App\Role;
use App\Tutor;
use App\Student;
use App\Relationship;

class TestCase extends Illuminate\Foundation\Testing\TestCase 
{

    protected $baseUrl = 'http://tutora.local';

    public function setUp()
    {
        parent::setUp();

        $this->artisan('db:seed');
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        return $app;
    }

    /**
     * Assert that a given where condition exists in the database.
     *
     * @param  string  $table
     * @param  integer $count
     * @param  array   $data
     * @param  string  $connection
     * @return $this
     */
    protected function seeCountInDatabase(
        $table,
        $count,
        array $data,
        $connection = null
    ) {
        $database   = $this->app->make('db');
        $connection = $connection ?: $database->getDefaultConnection();

        $actual = $database
            ->connection($connection)
            ->table($table)
            ->where($data)
            ->count();

        $this->assertEquals($count, $actual, sprintf(
            'Unable to find [%s] rows in database table [%s] that matched attributes [%s].', $count, $table, json_encode($data)
        ));

        return $this;
    }

    /**
     * Creates a user with the role of a tutor
     *
     * @param  array $attributes
     * @return User
     */
    public function createTutor(Array $attributes = [])
    {
        $user = factory(User::class, Tutor::class)->create($attributes);

        $user->roles()->attach(
            Role::where('name', '=', Role::TUTOR)->first()
        );

        return User::cast($user);
    }

    /**
     * Creates a user with the role of a student
     *
     * @param  array $attributes
     * @return User
     */
    public function createStudent(Array $attributes = [])
    {
        $user = factory(User::class, Student::class)->create($attributes);

        $user->roles()->attach(
            Role::where('name', '=', Role::STUDENT)->first()
        );

        return User::cast($user);
    }

    /**
     * Create a relationship between a given tutor and student
     *
     * @param  Tutor   $tutor
     * @param  Student $student
     * @param  array   $attributes
     * @return Relationship
     */
    public function createRelationship(
        Tutor   $tutor,
        Student $student,
        Array   $attributes = []
    ) {
        $attributes = array_extend([
            'tutor_id'   => $tutor->id,
            'student_id' => $student->id,
        ], $attributes);

        return factory(Relationship::class)
            ->create($attributes);
    }

}
