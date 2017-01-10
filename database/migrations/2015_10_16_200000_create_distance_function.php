<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistanceFunction extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE FUNCTION DISTANCE(my_latitude DECIMAL(18, 15), my_longitude DECIMAL(18, 15), your_latitude DECIMAL(18, 15), your_longitude DECIMAL(18, 15))
                RETURNS DECIMAL(18,15)
                BEGIN
                    DECLARE distance DECIMAL(18,15);
                    SET distance = 3959 * acos(
                        sin(radians(my_latitude))
                        * sin(radians(your_latitude))
                        + cos(radians(my_latitude))
                        * cos(radians(your_latitude))
                        * cos(radians(your_longitude) - radians(my_longitude))
                    );
                    RETURN distance;
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
        DB::unprepared('DROP FUNCTION IF EXISTS DISTANCE;');
    }

}
