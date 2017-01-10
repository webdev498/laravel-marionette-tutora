<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDistanceScoreFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS DISTANCE_SCORE;');

        DB::unprepared('
            CREATE FUNCTION DISTANCE_SCORE(distance DECIMAL(18, 15))
            RETURNS DECIMAL(18,15)

            BEGIN
                
                DECLARE score DECIMAL(18,15);
                SET score = 1/(1 + EXP(distance -3));
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
        DB::unprepared('DROP FUNCTION IF EXISTS DISTANCE_SCORE;');

        DB::unprepared('
            CREATE FUNCTION DISTANCE_SCORE(distance DECIMAL(18, 15))
                RETURNS DECIMAL(18,15)
                BEGIN
                    DECLARE score DECIMAL(18,15);
                    SET score = 10 - (distance / 2);
                    IF score < 0
                    THEN
                        SET score = 0;
                    END IF;
                    RETURN score;
            END
        ');
    }
}
