<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuildMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guild_members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('character_name')->index();
            $table->string('character_name_hash_lookup')->index();
            $table->integer('rank')->index();
            $table->integer('average_item_level')->index()->nullable();
            $table->integer('average_item_level_equipped')->index()->nullable();
            $table->integer('class')->index();
            $table->string('spec')->index()->nullable();
            $table->string('archetype')->index();
            $table->string('role')->index();
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
        Schema::dropIfExists('guild_members');
    }
}
