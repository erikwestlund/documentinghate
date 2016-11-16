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

            $table->string('title');
            $table->date('date');

            $table->string('city');
            $table->string('state');
            $table->string('location_name')->nullable();

            $table->string('source');
            $table->text('source_other_description')->nullable();

            $table->text('source_url')->nullable();
            $table->text('social_media_url')->nullable();

            $table->boolean('verbal_abuse')->default(false);
            $table->boolean('harassment')->default(false);
            $table->boolean('intimidation')->default(false);
            $table->boolean('physical_violence')->default(false);
            $table->boolean('vandalism')->default(false);
            $table->boolean('property_crime')->default(false);
            $table->string('other_incident_type')->default(false);
            $table->string('other_incident_description')->nullable();

            $table->text('description');

            $table->text('photo_url')->nullable();
            $table->text('thumbnail_photo_url')->nullable();

            $table->string('google_maps_place_id')->nullable();
            $table->decimal('google_maps_latitude', 10, 8)->nullable();
            $table->decimal('google_maps_longitude', 11, 8)->nullable();

            $table->text('submitter_email')->nullable();          
            $table->ipAddress('ip');
            $table->string('user_agent')->nullable();

            $table->boolean('approved')->nullable();

            $table->integer('approved_by_user_id')->unsigned()->index()->nullable();
            $table->foreign('approved_by_user_id')->references('id')->on('users');

            $table->text('approval_decision_comment')->nullable();

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
