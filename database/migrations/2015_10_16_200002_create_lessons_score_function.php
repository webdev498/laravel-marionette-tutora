<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonsScoreFunction extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE FUNCTION LESSONS_SCORE(lessons_count SMALLINT(5))
                RETURNS DECIMAL(18,15)
                BEGIN
                    DECLARE score DECIMAL(18,15);
                    SET score = SQRT(lessons_count);
                    IF score > 10
                    THEN
                        SET score = 10;
                    END IF;
                    RETURN score;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS LESSONS_SCORE;');
    }

}
