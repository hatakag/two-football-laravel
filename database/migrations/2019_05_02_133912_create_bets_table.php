<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('bet')) {
            Schema::create('bet', function (Blueprint $table) {
                $table->unsignedInteger('user_id');
                $table->integer('match_id');
                $table->integer('bet_type');
                $table->integer('bet_amount');
                $table->string('bet_content', 10);
                $table->timestamp('bet_time');
                $table->string('bet_status', 12)->nullable();
                $table->integer('bet_gain')->nullable();
                $table->primary(['user_id', 'match_id', 'bet_type']);
                $table->foreign('user_id')->references('user_id')->on('user');
                $table->foreign('match_id')->references('match_id')->on('fixture');
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
        Schema::dropIfExists('bet');
    }
}
