<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRankingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rankings', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('fight_start_time');
            $table->bigInteger('fight_start_time_raw');
            $table->string('character_name')->index();
            $table->string('zone')->index();
            $table->string('encounter')->index();
            $table->string('difficulty')->index();
            $table->string('metric', 5)->index();
            $table->integer('rank');
            $table->integer('out_of');
            $table->integer('percentile')->index();
            $table->string('report_id')->index();
            $table->integer('fight_id')->index();
            $table->integer('size');
            $table->integer('item_level');
            $table->integer('total');
            $table->integer('fight_duration');
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
        Schema::dropIfExists('rankings');
    }
}
