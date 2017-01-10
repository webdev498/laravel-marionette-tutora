<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedMissingUserRequirements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::insert("
            insert into user_requirements (`user_id`, `name`, `section`, `for`, `created_at`, `updated_at`)
            select `users`.`id`, 'payment_details', 'account', 'payouts', NOW(), NOW()
            from `users`
            left join `role_user`
            on `users`.`id` = `role_user`.`user_id`
            where `last_four` is null
            and `role_user`.`role_id` = 1;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete("delete from user_requirements where name = 'payment_details';");
    }
}
