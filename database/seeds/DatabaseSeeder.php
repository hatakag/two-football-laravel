<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('user')->insert([
            'user_id' => 1,
            'username' => 'thanh',
            'password' => bcrypt('secret'),
            'role' => config('constants.role.admin'),
            'balance' => 100000,
            'name' => 'thanh',
            'picture' => 'None',
            'phone' => '0123456789',
            'email' => 'thanh'.'@gmail.com',
        ]);
    }
}
