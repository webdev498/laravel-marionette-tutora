<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->smallInteger('any')->default(1);
            $table->smallInteger('signup')->default(1);
            $table->smallInteger('marketing')->default(1);
            $table->smallInteger('message_email')->default(1);
            $table->smallInteger('message_sms')->default(1);
            $table->smallInteger('job_opportunities')->default(1);
            $table->string('job_opportunities_frequency')->default('daily');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });

        $users = DB::select("SELECT users.id from users ORDER BY users.id");

        foreach ($users as $user)
        {
            DB::insert('
                insert into user_subscriptions

                    (user_id, any, signup, marketing, message_email, message_sms, job_opportunities, job_opportunities_frequency, created_at, updated_at)
                values (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());
            ', [
                $user->id,
                1,  // receive any communications
                1,  // receive signup reminders
                1,  // receive marketing notification emails
                1,  // received message notification sms
                1,  // receive marketing emails
                1,  // receive job opportunities
                'daily',  // frequency of job opportunities
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_subscriptions');
    }
}
