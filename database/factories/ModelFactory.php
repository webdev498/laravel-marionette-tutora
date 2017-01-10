<?php
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

// User
$factory->define(App\User::class, function (Faker\Generator $faker) {
    do {
        $uuid = str_uuid();
    } while (App\User::where('uuid', '=', $uuid)->count() > 0);

    $firstName = $faker->firstName;
    $lastName  = $faker->lastName;

    return [
        'confirmed'      => true,
        'uuid'           => $uuid,
        'first_name'     => $firstName,
        'last_name'      => $lastName,
        'dob'            => $faker->date('Y-m-d', '-18 years'),
        'email'          => "{$firstName}.{$lastName}@example.com",
        'telephone'      => $faker->randomElement(['+447', '01', '02', '03', '07']).$faker->randomNumber(9),
        'password'       => 'secret',
        'remember_token' => str_random(10),
    ];
});

$factory->defineAs(App\User::class, App\Admin::class, function (Faker\Generator $faker) use ($factory) {
    $raw = $factory->raw(App\User::class);

    return array_merge($raw, [
        'legal_first_name' => $raw['first_name'],
        'legal_last_name'  => $raw['last_name'],
        'email'            => "{$raw['first_name']}.{$raw['last_name']}@admin.com",
    ]);
});

$factory->defineAs(App\User::class, App\Tutor::class, function (Faker\Generator $faker) use ($factory) {
    $raw = $factory->raw(App\User::class);

    return array_merge($raw, [
        'legal_first_name' => $raw['first_name'],
        'legal_last_name'  => $raw['last_name'],
        'email'            => "{$raw['first_name']}.{$raw['last_name']}@tutor.com",
    ]);
});

$factory->defineAs(App\User::class, App\Student::class, function (Faker\Generator $faker) use ($factory) {
    $raw = $factory->raw(App\User::class);

    return array_merge($raw, [
        'email' => "{$raw['first_name']}.{$raw['last_name']}@student.com",
    ]);
});


// Addresses
$index     = 0;
$database  = DB::connection('sqlite_seed_sheffield_postcodes');
$addresses = $database->select('select * from addresses;');

$factory->define(App\Address::class, function (Faker\Generator $faker) use (&$index, $addresses) {
    $address = $addresses[$index++];

    return [
        'line_1'     => $faker->streetName,
        'line_2'     => $faker->boolean() ? $faker->streetName : null,
        'line_3'     => 'Sheffield',
        'postcode'   => $address->postcode,
        'latitude'   => $address->lat,
        'longitude'  => $address->lng,
    ];
});

// Location
$factory->define(App\Location::class, function (Faker\Generator $faker) use ($addresses) {

    $uuid = App\Location::generateUuid();
    $date = $faker->dateTimeBetween('-2 months', '-12 hours');

    return [
        'uuid'       => $uuid,
        'street'     => $faker->streetName,
        'city'       => 'Sheffield',
        'county'     => null,
        'country'    => null,
        'created_at' => $date,
        'updated_at' => $date,
    ];
});

// Relationship
$factory->define(App\Relationship::class, function (Faker\Generator $faker) {
    return [
        'is_confirmed' => $faker->boolean(),
        'status'       => 'pending',
    ];
});

// Message
$factory->define(App\Message::class, function (Faker\Generator $faker) {
    do {
        $uuid = str_uuid();
    } while (App\Message::where('uuid', '=', $uuid)->count() > 0);

    $date = $faker->dateTimeBetween('-2 months', '-12 hours');

    return [
        'uuid'       => $uuid,
        'created_at' => $date,
        'updated_at' => $date,
    ];
});

$factory->define(App\MessageLine::class, function (Faker\Generator $faker) {
    return [
        'body' => $faker->text(140),
    ];
});

$factory->define(App\MessageStatus::class, function (Faker\Generator $faker) {
    return [
        'unread' => rand(0, 1),
        'archived' => rand(0, 1)
    ];
});

// Task
$factory->define(App\Task::class, function (Faker\Generator $faker) {
    return [
        'body'      => $faker->sentence,
        'action_at' => $faker->dateTimeBetween('now', '+1 month'),
    ];
});

// UserProfile
$factory->define(App\UserProfile::class, function (Faker\Generator $faker) {
    list($status, $adminStatus, $required) = $faker->randomElement([
        [App\UserProfile::SNEW, App\UserProfile::PENDING, App\UserRequirement::PROFILE_INFORMATION],
        [App\UserProfile::SUBMITTABLE, App\UserProfile::PENDING, App\UserRequirement::PROFILE_INFORMATION],
        [App\UserProfile::SNEW, App\UserProfile::PENDING, App\UserRequirement::PROFILE_SUBMIT],
        [App\UserProfile::SUBMITTABLE, App\UserProfile::PENDING, App\UserRequirement::PROFILE_SUBMIT],
        [App\UserProfile::PENDING, App\UserProfile::REVIEW, App\UserRequirement::PAYOUTS],
        [App\UserProfile::LIVE, App\UserProfile::REJECTED, null],
        [App\UserProfile::LIVE, App\UserProfile::OK, null],
        [App\UserProfile::OFFLINE, App\UserProfile::OK, null],
    ]);

    return [
        'status'        => $status,
        'admin_status'  => $adminStatus,
        'required'      => $required,
        'quality'       => $faker->numberBetween(0, 10),
        'tagline'       => $faker->text($faker->numberBetween(30, 59)),
        'short_bio'     => str_limit(
            implode($faker->paragraphs($faker->numberBetween(1, 2)), "\n\n"),
            $faker->numberBetween(150, 300)
        ),
        'bio'           => implode(
            $faker->paragraphs($faker->numberBetween(3, 5)),
            "\n\n"
        ),
        'rate'          => $faker->numberBetween(10, 100),
        'rating'        => $faker->numberBetween(0, 50) / 10,
        'ratings_count' => $faker->numberBetween(0, 500),
        'lessons_count' => $faker->numberBetween(0, 500),
        'travel_radius' => $faker->randomElement([0, 1, 2, 5, 10, 20, 50]),
    ];
});

$factory->define(App\IdentityDocument::class, function (Faker\Generator $faker) {
    return [
        'billing_id' => str_random(),
        'uuid'       => str_uuid(true),
        'ext'        => App\IdentityDocument::EXT,
        'status'     => 'verified',
        'details'    => null,
    ];
});

// Qualifications
$factory->define('_qualification', function (Faker\Generator $faker) {
    return [
        'subject'        => $faker->sentence(3),
        'still_studying' => $faker->boolean(),
    ];
});

$factory->define(App\UserQualificationUniversity::class, function (Faker\Generator $faker) use ($factory) {
    return $factory->raw('_qualification', [
        'university' => $faker->sentence(4),
        'level'      => $faker->randomElement(config('qualifications.university.levels')),
    ]);
});

$factory->define(App\UserQualificationAlevel::class, function (Faker\Generator $faker) use ($factory) {
    return $factory->raw('_qualification', [
        'college' => $faker->sentence(4),
        'grade'   => $faker->randomElement(config('qualifications.alevels.grades')),
    ]);
});

$factory->define(App\UserQualificationOther::class, function (Faker\Generator $faker) use ($factory) {
    return $factory->raw('_qualification', [
        'location' => $faker->sentence(4),
        'grade'    => $faker->word(),
    ]);
});

$factory->define(App\UserQualificationTeacherStatus::class, function (Faker\Generator $faker) {
    return [
        'level' => $faker->randomElement(config('qualification.teacher_statuses.levels')),
    ];
});

// Background checks
$factory->define(App\UserBackgroundCheck::class, function (Faker\Generator $faker) {
    $dbs = $faker->boolean();

    return [
        'dbs'       => $dbs,
        'uuid'      => str_uuid(true),
        'issued_at' => $dbs ? $faker->dateTimeBetween('-2 years', 'now') : null,
    ];
});

// Requirements
$factory->define(App\UserRequirement::class, function (Faker\Generator $faker) {
    return [
        'is_completed' => false,
        'is_pending'   => false,
        'is_optional'  => false,
    ];
});

// Lesson
$factory->define(App\Lesson::class, function (Faker\Generator $faker) {
    $date = $faker->dateTimeBetween('-2 weeks', '+2 weeks');

    return [
        'duration'   => $faker->randomElement([1800, 2700, 3600, 4500, 5400, 7200]),
        'location'   => $faker->streetName,
        'status'     => App\Lesson::CONFIRMED,
        'created_at' => $date,
        'updated_at' => $date,
    ];
});

$factory->defineAs(App\LessonSchedule::class, 'weekly', function (Faker\Generator $faker) {
    return [
        'minute'           => $faker->randomElement([0, 30]),
        'hour'             => $faker->numberBetween(8, 20),
        'day_of_the_month' => -1,
        'month'            => -1,
        'day_of_the_week'  => $faker->numberBetween(0, 6),
        'nth'              => 1,
    ];
});

$factory->defineAs(App\LessonSchedule::class, 'fortnightly', function (Faker\Generator $faker) use ($factory) {
    return $factory->raw(App\LessonSchedule::class, [
        'nth' => 2,
    ], 'weekly');
});

$factory->define(App\LessonBooking::class, function (Faker\Generator $faker) use ($factory) {
    do {
        $uuid = str_uuid();
    } while (App\LessonBooking::where('uuid', '=', $uuid)->count() > 0);

    return [
        'uuid' => $uuid,
    ];
});

// Job
$factory->define(App\Job::class, function (Faker\Generator $faker) {

    $data = [];

    $uuid = App\Job::generateUuid();

    $date = $faker->dateTimeBetween('-2 months', '-12 hours');

    $status  = $faker->numberBetween(1, 3);
    $message = $faker->text();

    if($status == App\Job::STATUS_CLOSED) {
        $reasons = [
            App\Job::CLOSED_FOR_CONFIRMATION,
            App\Job::CLOSED_FOR_EXPIRATION,
            App\Job::CLOSED_FOR_APPLICATIONS,
            App\Job::CLOSED_FOR_MANUAL,
        ];
        $data['closed_for'] = $reasons[$faker->numberBetween(0,3)];
        $data['closed_at']  = $faker->dateTimeBetween('-12 months', '-1 hours');
    }

    if($status != App\Job::STATUS_PENDING) {
        $data['opened_at']  = $faker->dateTimeBetween('-12 months', '-1 hours');
    }

    return array_merge([
        'uuid'       => $uuid,
        'message'    => $message,
        'status'     => $status,
        'created_at' => $date,
        'updated_at' => $date,
    ], $data);
});

