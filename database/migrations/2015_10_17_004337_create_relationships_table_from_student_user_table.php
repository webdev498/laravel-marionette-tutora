<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationshipsTableFromStudentUserTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_user', function (Blueprint $table) {
            // create
            $table->unsignedInteger('tutor_id')->after('id');
            // user_id
            $table->dropForeign('student_user_user_id_foreign');
            $table->dropIndex('student_user_user_id_index');
            // student_id
            $table->dropForeign('student_user_student_id_foreign');
            $table->dropIndex('student_user_student_id_index');
        });

        DB::update('UPDATE `student_user` SET `tutor_id` = `user_id`;');

        Schema::rename('student_user', 'relationships');

        Schema::table('relationships', function (Blueprint $table) {
            // drop
            $table->dropColumn('user_id');
            // change
            $table->string('status')->default('pending')->after('confirmed');
            $table->renameColumn('confirmed', 'is_confirmed');
            // tutor_id
            $table->index('tutor_id');
            $table->foreign('tutor_id')->references('id')->on('users')->onDelete('cascade');
            // student_id
            $table->index('student_id');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('relationships', function (Blueprint $table) {
            // create
            $table->unsignedInteger('user_id')->after('id');
            // user_id
            $table->dropForeign('relationships_tutor_id_foreign');
            $table->dropIndex('relationships_tutor_id_index');
            // student_id
            $table->dropForeign('relationships_student_id_foreign');
            $table->dropIndex('relationships_student_id_index');
        });

        DB::update('UPDATE `relationships` SET `user_id` = `tutor_id`;');

        Schema::rename('relationships', 'student_user');

        Schema::table('student_user', function (Blueprint $table) {
            // drop
            $table->dropColumn('tutor_id', 'status');
            // change
            $table->renameColumn('is_confirmed', 'confirmed');
            // user_id
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // student_id
            $table->index('student_id');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

}
