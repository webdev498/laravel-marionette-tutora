<?php

use App\Role;

class AdminSeeder extends AbstractUserSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = $this->findRoles(Role::ADMIN, Role::TUTOR);

        $this->createUser([
            'first_name' => 'Aaron',
            'last_name'  => 'Lord',
            'email'      => 'aaron.lord@admin.com',
            'confirmed'  => true,
        ], $roles);

        $this->createUser([
            'first_name' => 'Mark',
            'last_name'  => 'Hughes',
            'email'      => 'mark.hughes@admin.com',
            'confirmed'  => true,
        ], $roles);

        $this->createUser([
            'first_name' => 'Scott',
            'last_name'  => 'Woodley',
            'email'      => 'scott.woodley@admin.com',
            'confirmed'  => true,
        ], $roles);

    }

}
