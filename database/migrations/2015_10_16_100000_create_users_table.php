<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table) {
            $table->increments('id');
            $table->boolean('confirmed')->nullable()->default(0);
            $table->string('billing_id')->nullable();
            $table->char('uuid', 36)->unique()->index();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('dob')->nullable();
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->smallInteger('last_four')->unsigned()->nullable();
            $table->string('password', 60);
            $table->rememberToken();
            $table->string('confirmation_token', 100)->nullable()->default(null);
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
        Schema::drop('users');
    }

}
