<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropUserProfileTasksTable extends Migration
{
    /**
     * A map of old => new task names as taken
     * from user_profile_tasks
     *
     * @var array
     */
    protected $map = [
        'profile_picture' => 'pic',
        'identification'  => 'identity_document',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('user_profile_tasks');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // create
        Schema::create('user_profile_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_profile_id')->unsigned()->index();
            $table->string('name');
            $table->timestamps();

            $table->foreign('user_profile_id')->references('id')->on('user_profiles')->onDelete('cascade');
        });

        // insert from user_requirements
        DB::insert('
            insert into user_profile_tasks (
                user_profile_id,
                name,
                created_at,
                updated_at
            )
            select up.id, ur.name, ur.created_at, NOW()
            from user_requirements as ur
            join users as u
            on u.id = ur.user_id
            join user_profiles as up
            on u.id = up.user_id
        ');

        // update names
        foreach ($this->map as $old => $new) {
            DB::table('user_profile_tasks')
                ->where('name', '=', $old)
                ->update(['name' => $new]);
        }

        DB::delete("delete from `user_profile_tasks` where `name` = 'payment_details';");
    }
}
