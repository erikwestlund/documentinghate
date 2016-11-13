<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->increments('id');

            $table->boolean('approved')->default(false);

            $table->string('title');
            $table->dateTime('date');

            $table->string('city');
            $table->string('state');

            $table->string('how_known');
            $table->text('how_known_other_description')->nullable();

            $table->boolean('verbal_abuse')->default(false);
            $table->boolean('harassment')->default(false);
            $table->boolean('intimidation')->default(false);
            $table->boolean('physical_violence')->default(false);
            $table->boolean('vandalism')->default(false);
            $table->boolean('property_crime')->default(false);
            $table->string('other_incident_type')->nullable();

            $table->text('description');

            $table->text('image_url')->nullable();
            $table->text('thumbnail_image_url')->nullable();

            $table->string('coordinates')->nullable();

            $table->text('submitter_email')->nullable();          
            $table->string('ip');
            $table->string('user_agent')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incidents');
    }
}
