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
                $table->integer('user_id');
                $table->integer('match_id');
                $table->integer('bet_type');
                $table->integer('bet_amount')->nullable();
                $table->string('bet_content', 10)->nullable();
                $table->timestamp('bet_time');
                $table->string('bet_status', 12)->nullable();
                $table->integer('bet_gain')->nullable();
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
