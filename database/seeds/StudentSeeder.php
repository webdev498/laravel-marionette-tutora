<?php

use App\Role;

class StudentSeeder extends AbstractUserSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = $this->findRoles(Role::STUDENT);

        $this->createUser([
            'uuid'       => 'melissa.lord',
            'first_name' => 'Melissa',
            'last_name'  => 'Lord',
            'email'      => 'melissa.lord@student.com',
            'confirmed'  => true,
        ], $roles);

        $this->createUser([
            'uuid'       => 'scott.woodley',
            'first_name' => 'Scott',
            'last_name'  => 'Woodley',
            'email'      => 'scott.woodley@student.com',
            'confirmed'  => true,
        ], $roles);

        $this->createUsers(config('seeding.users'), [], $roles);
    }
}
