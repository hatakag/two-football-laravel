<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('transaction')) {
            Schema::create('transaction', function (Blueprint $table) {
                $table->string('type', 10)->nullable();
                $table->timestamp('time');
                $table->integer('user_id');
                $table->integer('amount')->nullable();
                $table->string('description', 60)->nullable();
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
        Schema::dropIfExists('transaction');
    }
}
