<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreatedForTutorColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tuition_job_tutor', function (Blueprint $table) {
            $table->boolean('created_for_tutor')->default(false)->after('applied');
        });

        $jobsCreatedForTutors = DB::select('
            SELECT
            relationships.tutor_id as tutor_id,
            tuition_jobs.id as job_id,
            tuition_job_tutor.id as job_tutor_id
            FROM
            tuition_jobs
            LEFT JOIN
            tuition_job_message_line ON tuition_job_message_line.job_id = tuition_jobs.id
            LEFT JOIN
            message_lines ON (message_lines.id = tuition_job_message_line.message_line_id)
            LEFT JOIN
            messages ON messages.id = message_lines.message_id
            LEFT JOIN
            relationships ON relationships.id = messages.relationship_id
            LEFT JOIN
            tuition_job_tutor ON (tuition_job_tutor.user_id = relationships.tutor_id AND tuition_job_tutor.job_id = tuition_jobs.id)
            WHERE
            tuition_jobs.user_id = message_lines.user_id
        ');

        foreach($jobsCreatedForTutors as $data) {
            if($data->job_tutor_id) {
                DB::update("
                    UPDATE
                    tuition_job_tutor
                    SET created_for_tutor=1
                    WHERE
                    tuition_job_tutor.id = {$data->job_tutor_id}
                ");
            } else {
                DB::insert("
                    INSERT INTO
                    tuition_job_tutor
                    (user_id, job_id, created_for_tutor, created_at, updated_at)
                    VALUES
                    ({$data->tutor_id}, {$data->job_id}, 1, NOW(), NOW())
                ");
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tuition_job_tutor', function (Blueprint $table) {
            $table->dropColumn('created_for_tutor');
        });
    }
}
