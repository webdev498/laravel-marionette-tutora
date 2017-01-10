<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->guardAgainstProduction();

        $this->unguardModels();

        $this->truncateTable(
            $this->getTableNames()
        );

        $this->callSeeder([
            'SubjectSeeder',
            'RoleSeeder',
            'AdminSeeder',
            'TutorSeeder',
            'StudentSeeder',
            'AddressSeeder',
            'LocationSeeder',
            'RelationshipSeeder',
            'TasksSeeder',
            'ProfileSeeder',
            'IdentityDocumentSeeder',
            'RequirementSeeder',
            'MessageSeeder',
            'LessonSeeder',
            'ArticleSeeder',
            'JobSeeder',
            'UserDialogueSeeder',
        ]);

        $this->guardModels();
    }

    /**
     * Guard against seeding the database in production
     *
     * @return void
     */
    protected function guardAgainstProduction()
    {
        if (environment('production')) {
            die('Seeding in production. Not a good idea, champ.');
        }
    }

    /**
     * Get a list of all the table names from the database
     *
     * @return array
     */
    protected function getTableNames()
    {
        $results = DB::table('information_schema.tables')
            ->select('table_name')
            ->where('table_schema', '=', env('DB_DATABASE'))
            ->where('table_name', '!=', 'migrations')
            ->get();

        return array_map(function ($result) {
            return $result->table_name;
        }, $results);
    }

    /**
     * Truncate the given table(s)
     *
     * @param  mixed $tables
     * @return void
     */
    protected function truncateTable($tables)
    {
        foreach ((array) $tables as $table) {
            DB::table($table)->delete();
            DB::table($table)->truncate();
        }
    }

    /**
     * Run the given seeder(s)
     *
     * @param  mixed $classes
     * @return void
     */
    protected function callSeeder($classes)
    {
        foreach ((array) $classes as $class) {
            $this->call($class);
        }
    }

    /**
     * Unguard the database
     *
     * @return void
     */
    protected function unguardModels()
    {
        if (config('database.default') === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        Model::unguard();
    }

    /**
     * Guard the database
     *
     * @return void
     */
    protected function guardModels()
    {
        if (config('database.default') === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        Model::reguard();
    }

}
