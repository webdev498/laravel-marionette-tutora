<?php

use App\Message;
use Carbon\Carbon;
use App\MessageLine;
use App\MessageStatus;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getRelationships() as $relationship) {
            $message = $relationship->message()->save(
                factory(Message::class)->make()
            );

            $tutor = $relationship->tutor;
            $student = $relationship->student;

            factory(MessageStatus::class, 1)->create([
                'message_id' => $message->id,
                'user_id' => $tutor->id
            ]);

            factory(MessageStatus::class, 1)->create([
                'message_id' => $message->id,
                'user_id' => $student->id
            ]);

            $date = $message->created_at;

            $message->lines()->saveMany(
                factory(MessageLine::class, rand(2, 10))
                    ->make()
                    ->each(function ($line) use ($relationship, $message, &$date) {
                        $date = $date->addMinutes(rand(5, 60));

                        $line->created_at = $date;
                        $line->updated_at = $date;

                        $line->user()
                            ->associate(
                                rand(0, 1)
                                ? $relationship->tutor
                                : $relationship->student
                            );
                    })
            );
        }
    }
}
