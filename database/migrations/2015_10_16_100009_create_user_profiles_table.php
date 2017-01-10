<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->boolean('reviewed')->unsigned()->default(0);
            $table->boolean('rejected')->unsigned()->default(0);
            $table->boolean('live')->unsigned()->default(0);
            $table->boolean('featured')->unsigned()->default(0);
            $table->tinyInteger('quality')->unsigned()->default(0);
            $table->string('tagline', 100)->nullable();
            $table->text('short_bio')->nullable();
            $table->text('bio')->nullable();
            $table->smallInteger('rate')->unsigned()->nullable();
            $table->double('rating', 2, 1)->unsigned()->default(0);
            $table->smallInteger('ratings_count')->unsigned()->default(0);
            $table->smallInteger('lessons_count')->unsigned()->default(0);
            $table->tinyInteger('travel_radius')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_profiles');
    }

}
