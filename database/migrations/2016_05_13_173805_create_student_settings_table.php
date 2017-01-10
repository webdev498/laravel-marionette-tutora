<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Student;

class CreateStudentSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->boolean('retry_failed_payments')->unsigned()->default(1);
            $table->boolean('send_failed_payment_notifications')->unsigned()->default(1);
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });

        $students = DB::select("SELECT users.id from users 
            JOIN role_user ON role_user.user_id = users.id
            JOIN roles ON roles.id = role_user.role_id
            WHERE roles.name = 'student' ");

        foreach ($students as $student)
        {
            DB::insert('
                insert into student_settings
                    (user_id, retry_failed_payments, send_failed_payment_notifications, created_at, updated_at)
                values (?, ?, ?, NOW(), NOW());
            ', [
                $student->id,
                1,  //default retry payments
                1   // default to send charge failed notifications
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
        Schema::drop('student_settings');
    }
}
