<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImageForeignKeyForBackgroundCheck extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `user_background_checks` MODIFY `image_id` INTEGER UNSIGNED NULL;');
        DB::statement('UPDATE `user_background_checks` SET `image_id` = NULL WHERE `image_id` = 0;');

        Schema::table('user_background_checks', function (Blueprint $table) {

            $table->foreign('image_id')
                ->references('id')->on('images')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_background_checks', function (Blueprint $table) {
            $table->dropForeign('user_background_checks_image_id_foreign');
        });

        DB::statement('UPDATE `user_background_checks` SET `image_id` = 0 WHERE `image_id` IS NULL;');
        DB::statement('ALTER TABLE `user_background_checks` MODIFY `image_id` INTEGER UNSIGNED NOT NULL;');
    }
}
