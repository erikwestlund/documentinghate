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

            $table->text('description')->nullable();
            
            $table->text('image_url')->nullable();
            $table->text('thumbnail_image_url')->nullable();

            $table->dateTime('when');

            $table->string('location')->nullable();
            $table->string('city');
            $table->string('state');

            $table->string('coordinates')->nullable();
          
            $table->string('ip');
            $table->string('user_agent')->nullable();
            $table->string('google_utma')->nullable();

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
