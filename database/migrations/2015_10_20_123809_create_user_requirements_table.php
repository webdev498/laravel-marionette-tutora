<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRequirementsTable extends Migration
{
    /**
     * A map of old => new task names as taken
     * from user_profile_tasks
     *
     * @var array
     */
    protected $map = [
        'pic'               => 'profile_picture',
        'identity_document' => 'identification'
    ];

    /**
     * A map of section => [tasks]
     *
     * @var array
     */ 
    protected $sections = [
        'profile' => [
            'tagline',
            'rate',
            'bio',
            'profile_picture',
            'subjects',
            'qualifications',
            'travel_policy',
            'qualified_teacher_status',
            'personal_video'
        ],
        'account' => [
            'payment_details',
            'identification',
        ],
        'other' => [
            'dbs_check',
        ],
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create
        Schema::create('user_requirements', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->string('name');
            $table->string('section');
            $table->boolean('is_optional')->unsigned()->deafult(0);
            $table->boolean('is_completed')->unsigned()->deafult(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // insert from user_profile_tasks
        DB::insert('
            insert into user_requirements (
                user_id,
                name,
                section,
                created_at,
                updated_at
            )
            select p.user_id, t.name, "other" as section, t.created_at, NOW()
            from user_profile_tasks as t
            join user_profiles as p
            on p.id = t.user_profile_id
        ');

        // update names
        foreach ($this->map as $old => $new) {
            DB::table('user_requirements')
                ->where('name', '=', $old)
                ->update(['name' => $new]);
        }

        // update sections
        foreach ($this->sections as $section => $names) {
            DB::table('user_requirements')
                ->whereIn('name', $names)
                ->update(['section' => $section]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_requirements');
    }
}
