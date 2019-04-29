<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('user')) {
            Schema::create('user', function (Blueprint $table) {
                $table->increments('user_id');
                $table->string('username', 100)->unique();
                $table->string('password', 600);
                $table->string('role', 10);
                $table->float('balance');
                $table->string('name', 60);
                $table->string('picture', 300)->nullable();
                $table->string('phone', 11)->unique();
                $table->string('email', 100)->unique();
                //$table->rememberToken();
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
        Schema::dropIfExists('user');
    }
}
