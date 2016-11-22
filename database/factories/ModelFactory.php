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

$incident = new App\Incident;
$user = new App\User;


$factory->define(App\User::class, function (Faker\Generator $faker) use ($user) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'moderation_notification_frequency' => $faker->randomElement( $user->notification_levels), 
    ];
});

$factory->define(App\Incident::class, function (Faker\Generator $faker) use ($incident) {

    return [
        'title' => $faker->sentence,
        'date' => $faker->dateTimeBetween('11/08/2016', 'now'),
        'city' => $faker->city,
        'state' => $faker->stateAbbr,
        'location_name' => $faker->streetName,
        'source' => $faker->randomElement($incident->source_dictionary), // array('c'
        'source_other_description' => $faker->text,
        'news_article_url' => $faker->url,
        'social_media_url' => $faker->url,
        'description' => $faker->paragraphs(5, true),
        'photo_url' => $faker->imageUrl,        
        'thumbnail_photo_url' => $faker->imageUrl(300, 300),        
        'google_maps_place_id' => $faker->regexify('[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,9}'),
        'latitude' => $faker->latitude(26, 48),
        'longitude' => $faker->longitude(-125, -63),
        'submitter_email' => $faker->email,
        'email_when_approved' => $faker->boolean(60),
        'approval_email_sent' => $faker->dateTimeBetween('-1 month', 'now'),
        'ip' => $faker->ipv4,
        'user_agent' => $faker->userAgent,
        'approved' => $faker->boolean,
        'created_at' => $faker->dateTimeBetween('11/08/2016', 'now'),
    ];
});
