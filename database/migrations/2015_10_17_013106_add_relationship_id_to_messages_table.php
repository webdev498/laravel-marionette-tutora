<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationshipIdToMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // add
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedInteger('relationship_id')->index()->after('id');
        });

        // Find the relationship ids based on who's in message_user
        $results = DB::select('
            select
                relationships.id as relationship_id,
                messages.id as message_id,
                message_tutor.tutor_id,
                message_student.student_id
            from messages
            join (
                select message_user.message_id, message_user.user_id as tutor_id
                from message_user
                join role_user
                on role_user.user_id    = message_user.user_id
                where role_user.role_id = 1
            ) as message_tutor
            on message_tutor.message_id = messages.id
            join (
                select message_user.message_id, message_user.user_id as student_id
                from message_user
                join role_user
                on role_user.user_id    = message_user.user_id
                where role_user.role_id = 2
            ) as message_student
            on message_student.message_id = messages.id
            join relationships
            on relationships.tutor_id = message_tutor.tutor_id
            and relationships.student_id = message_student.student_id;
        ');

        foreach ($results as $result) {
            DB::update('update messages set relationship_id = ? where id = ?', [
                $result->relationship_id,
                $result->message_id
            ]);
        }

        // foreign
        Schema::table('messages', function (Blueprint $table) {
            $table->foreign('relationship_id')->references('id')->on('relationships')->onDelete('cascade');
        });

        // Drop message_user
        Schema::drop('message_user');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('message_user', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('message_id')->index();
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Populate the message_users table with tutors & students form the relation
        DB::insert('
            insert into message_user (message_id, user_id, created_at, updated_at)
            select messages.id, relationships_tutor.user_id, now(), now()
            from messages
            join (
            select id as relationship_id, tutor_id as user_id
                from relationships
            ) as relationships_tutor
            on relationships_tutor.relationship_id = messages.relationship_id
            union
            select messages.id, relationships_student.user_id, now(), now()
            from messages
            join (
            select id as relationship_id, student_id as user_id
                from relationships
            ) as relationships_student
            on relationships_student.relationship_id = messages.relationship_id
            order by id
        ');

        // add
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign('messages_relationship_id_foreign');
            $table->dropIndex('messages_relationship_id_index');
            $table->dropColumn('relationship_id');
        });
    }
}
