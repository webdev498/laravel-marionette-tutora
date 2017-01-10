<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationshipIdToLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // add
        Schema::table('lessons', function (Blueprint $table) {
            $table->unsignedInteger('relationship_id')->index()->after('id');
        });

        // fill
        DB::update('
            update lessons
            join relationships
            on relationships.tutor_id = lessons.tutor_id
            and relationships.student_id = lessons.student_id
            set lessons.relationship_id = relationships.id
        ');

        // foreign & drop
        Schema::table('lessons', function (Blueprint $table) {
            $table->foreign('relationship_id')->references('id')->on('relationships')->onDelete('cascade');

            // tutor_id
            $table->dropForeign('lessons_tutor_id_foreign');
            $table->dropIndex('lessons_tutor_id_index');
            $table->dropColumn('tutor_id');

            // student_id
            $table->dropForeign('lessons_student_id_foreign');
            $table->dropIndex('lessons_student_id_index');
            $table->dropColumn('student_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // add
        Schema::table('lessons', function (Blueprint $table) {
            $table->unsignedInteger('tutor_id')->index()->after('id');
            $table->unsignedInteger('student_id')->index()->after('tutor_id');
        });

        // fill
        DB::update('
            update lessons
            join relationships
            on relationships.id = lessons.relationship_id
            set lessons.tutor_id = relationships.tutor_id,
            lessons.student_id = relationships.student_id
        ');

        // foreign & drop
        Schema::table('lessons', function (Blueprint $table) {
            $table->foreign('tutor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');

            // relationship_id
            $table->dropForeign('lessons_relationship_id_foreign');
            $table->dropIndex('lessons_relationship_id_index');
            $table->dropColumn('relationship_id');
        });
    }
}
