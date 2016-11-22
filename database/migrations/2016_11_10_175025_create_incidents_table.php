<?php

use App\Incident;
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
        $incident = new Incident;

        Schema::create('incidents', function (Blueprint $table) use ($incident) {
            $table->increments('id');

            $table->string('title');
            $table->date('date');

            $table->string('city');
            $table->string('state');
            $table->string('location_name')->nullable();

            $table->enum('source', $incident->source_dictionary);
            $table->text('source_other_description')->nullable();

            $table->text('news_article_url')->nullable();
            $table->text('social_media_url')->nullable();

            $table->text('description');

            $table->text('photo_url')->nullable();
            $table->text('thumbnail_photo_url')->nullable();

            $table->string('google_maps_place_id')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->text('submitter_email');
            $table->boolean('email_when_approved')->default(false);
            $table->datetime('approval_email_sent')->nullable();

            $table->ipAddress('ip');
            $table->string('user_agent')->nullable();

            $table->boolean('approved')->nullable();
            $table->text('slug');

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
