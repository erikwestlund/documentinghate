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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Incident::class, function (Faker\Generator $faker) {

    return [
        'title' => $faker->sentence,
        'date' => $faker->dateTimeBetween('11/08/2016', 'now'),
        'city' => $faker->city,
        'state' => $faker->stateAbbr,
        'location_name' => $faker->streetName,
        'source' => $faker->randomElement(['news_article', 'i_witnessed_it','someone_i_know_witnessed_it','social_media', 'other']), // array('c'
        'source_other_description' => $faker->text,
        'news_article_url' => $faker->url,
        'social_media_url' => $faker->url,
        'verbal_abuse' => $faker->boolean(20),
        'intimidation' => $faker->boolean(20),
        'physical_violence' => $faker->boolean(20),
        'vandalism' => $faker->boolean(20),
        'property_crime' => $faker->boolean(20),
        'other' => $faker->boolean(10),
        'other_incident_description' => $faker->sentence,
        'description' => $faker->paragraphs(5, true),
        'photo_url' => $faker->imageUrl,        
        'thumbnail_photo_url' => $faker->imageUrl(300, 300),        
        'google_maps_place_id' => $faker->regexify('[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,9}'),
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,        
        'submitter_email' => $faker->email,
        'ip' => $faker->ipv4,
        'user_agent' => $faker->userAgent,
        'approved' => $faker->boolean,
    ];
});
