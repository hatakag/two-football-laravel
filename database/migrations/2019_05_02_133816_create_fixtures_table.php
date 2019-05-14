<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFixturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('fixture')) {
            Schema::create('fixture', function (Blueprint $table) {
                $table->integer('match_id')->primary();
                $table->integer('league_id');
                $table->string('match_date', 40);
                $table->string('match_time', 40);
                $table->string('match_hometeam_name', 60);
                $table->string('match_awayteam_name', 60);
                $table->integer('match_hometeam_halftime_score')->nullable();
                $table->integer('match_awayteam_halftime_score')->nullable();
                $table->integer('match_hometeam_score')->nullable();
                $table->integer('match_awayteam_score')->nullable();
                $table->integer('yellow_card')->nullable();
                $table->string('match_status', 6)->nullable();
                $table->foreign('league_id')->references('league_id')->on('league');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixture');
    }
}
