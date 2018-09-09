<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('week');
            $table->boolean('is_played')->default(false);
            $table->unsignedInteger('home_club_id');
            $table->unsignedInteger('away_club_id');
            $table->integer('home_club_goals');
            $table->integer('away_club_goals');

            // foreign keys
            $table->foreign('home_club_id')->references('id')->on('clubs')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('away_club_id')->references('id')->on('clubs')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
