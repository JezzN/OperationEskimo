<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLootDropsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loot_drops', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('loot_time')->index();
            $table->bigInteger('raw_time')->index();
            $table->string('character_name')->index();
            $table->integer('item_id')->index();
            $table->string('context')->index();
            $table->string('bonus_list');
            $table->string('name')->index();
            $table->integer('item_level')->index();
            $table->integer('quality')->index();
            $table->boolean('equippable');
            $table->integer('required_character_level');
            $table->boolean('has_sockets');
            $table->string('tooltip_description');
            $table->string('tooltip_color');
            $table->string('heroic_tooltip');
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
        Schema::dropIfExists('loot_drops');
    }
}
