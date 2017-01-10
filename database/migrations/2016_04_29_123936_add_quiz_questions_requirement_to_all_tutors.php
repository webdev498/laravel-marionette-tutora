<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddQuizQuestionsRequirementToAllTutors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("INSERT INTO user_requirements (`user_id`, `name`, `section`, `for`, `is_optional`, `is_pending`, `is_completed`, `created_at`, `updated_at`) SELECT ru.user_id, 'quiz_questions', 'quiz', 'profile_submit', 0, 1, 0, NOW(), NOW() FROM role_user ru INNER JOIN roles r ON r.id = ru.role_id LEFT JOIN user_requirements ur ON ur.user_id = ru.user_id AND ur.name = 'quiz_questions' WHERE r.name = 'Tutor' AND ur.name IS NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
