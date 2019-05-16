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

        DB::table('card')->insert([
            'code' => 123,
            'value' => 100000,
            'active' => true,
        ]);

        DB::table('league')->insert([[
            'league_id' => 128,
            'league_name' => 'Ligue 2',
            'country' => 'France',
        ],[
            'league_id' => 99999,
            'league_name' => 'null',
            'country' => 'null',
        ]]);
    }
}
