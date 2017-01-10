<?php

use App\Resources\Article;
use Faker\Factory;

class ArticleSeeder extends Seeder {

public function run()
    {
        $faker = Factory::create();
        $date = $faker->dateTimeBetween('-2 months', '-12 hours');

        DB::table('resources_articles')->delete();

        Article::create([
            'user_id' =>1,
            'title' => 'First Article',
            'body' => 'body of article',
            'published' => true,
            'published_at' => $date,
        ]);

        Article::create([
            'user_id' =>3,
            'title' => 'Second Article',
            'body' => 'body of article',
            'published' => false,
            'published_at' => $date,
        ]);

        Article::create([
            'user_id' =>2,
            'title' => 'Third Article',
            'body' => 'body of article',
            'published' => true,
            'published_at' => $date,
        ]);
    }
}