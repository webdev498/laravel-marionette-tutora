<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeResourcesArticlesTableAddLongtextField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE resources_articles MODIFY COLUMN body MEDIUMTEXT');
        DB::statement('ALTER TABLE resources_articles MODIFY COLUMN image MEDIUMTEXT');


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE resources_articles MODIFY COLUMN body TEXT');
        DB::statement('ALTER TABLE resources_articles MODIFY COLUMN image TEXT');
    }
}
