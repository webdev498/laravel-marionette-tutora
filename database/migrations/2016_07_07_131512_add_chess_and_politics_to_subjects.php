<?php

use App\Subject;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChessAndPoliticsToSubjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Cache::flush();
        $politics = Subject::where('name', '=', 'Politics')->first();
        $humanities = Subject::where('name', '=', 'Humanities (including the arts) and Social Sciences')->first();

        if($humanities) {
            $humanities->appendNode($politics);
        }
        Cache::flush();
        $gcse = Subject::create([
            'name' => 'GCSE',
        ]);
        Cache::flush();
        $alevel = Subject::create([
            'name' => 'A-Level',
        ]);
        Cache::flush();
        $degree = Subject::create([
            'name' => 'Degree',
        ]);

        Cache::flush();
        if($politics) {
            $politics->appendNode($gcse);
            Cache::flush();
            $politics->appendNode($alevel);
            Cache::flush();
            $politics->appendNode($degree);
            Cache::flush();
        }

        $chess = Subject::create([
            'name' => 'Chess',
        ]);
        Cache::flush();
        $sports = Subject::where('name', '=', 'Sports, Dance and Fitness')->first();
        Cache::flush();
        if($sports) {
            $sports->appendNode($chess);
            Cache::flush();
        }
        $modernStudies = Subject::create([
            'name' => 'Modern Studies',
        ]);
        Cache::flush();
        if($humanities) {
            $humanities->appendNode($modernStudies);
            Cache::flush();
        }
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
