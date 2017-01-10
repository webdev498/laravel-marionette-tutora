<?php

use App\Role;

class TutorSeeder extends AbstractUserSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = $this->findRoles(Role::TUTOR);

        $this->createUser([
            'uuid'       => 'aaron.lord',
            'first_name' => 'Aaron',
            'last_name'  => 'Lord',
            'email'      => 'aaron.lord@tutor.com',
            'dob'        => '1991-03-04',
        ], $roles);

        $this->createUser([
            'uuid'       => 'mark.hughes',
            'first_name' => 'Mark',
            'last_name'  => 'Hughes',
            'email'      => 'mark.hughes@tutor.com',
        ], $roles);

        $this->createUsers(config('seeding.users'), [], $roles);
    }

}
