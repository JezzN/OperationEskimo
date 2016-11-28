<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMmoChampionPostThread extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mmo_champion_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('publish_date')->index();
            $table->string('title')->index();
            $table->string('link')->index();
            $table->string('guid')->index();
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
        Schema::dropIfExists('mmo_champion_posts');
    }
}
