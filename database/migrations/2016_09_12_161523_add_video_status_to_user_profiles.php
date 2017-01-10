<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\UserRequirement;

class AddVideoStatusToUserProfiles extends Migration
{
    public function up()
    {
        $tutors = (new RequirementSeederHelper())->allTutors();

        Schema::table(
            'user_profiles',
            function (Blueprint $table) {
                $table->string(
                    'video_status',
                    16
                )->after('bio')->nullable();
            }
        );

        foreach ($tutors as $tutor) {
            $tutor->requirements()->save(
                factory(UserRequirement::class)->make(
                    [
                        'name'         => UserRequirement::PERSONAL_VIDEO,
                        'section'      => UserRequirement::OTHER,
                        'for'          => UserRequirement::ANY,
                        'is_completed' => false,
                        'is_pending'   => true,
                        'is_optional'  => true
                    ]
                )
            );
        }
    }

    public function down()
    {
        Schema::table(
            'user_profiles',
            function (Blueprint $table) {
                $table->dropColumn('video_status');
            }
        );

        DB::table('user_requirements')
            ->where(
                'name',
                '=',
                UserRequirement::PERSONAL_VIDEO
            )->delete();
    }
}

class RequirementSeederHelper extends Seeder
{
    public function allTutors()
    {
        return $this->getTutors();
    }
}