<?php

use App\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RoleSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Config::get('seeding.roles') as $name) {
            $role       = new Role();
            $role->name = $name;
            $role->save();
        }
    }

}
