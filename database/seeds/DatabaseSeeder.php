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

        DB::table('fixture')->insert([
            'match_id' => 410600,
            'league_id' => 128,
            'match_date' => '2019-05-10',
            'match_time' => '18:45',
            'match_hometeam_name' => 'AC Ajaccio',
            'match_awayteam_name' => 'Lens',
            'match_hometeam_halftime_score' => 0,
            'match_awayteam_halftime_score' => 0,
            'match_hometeam_score' => 0,
            'match_awayteam_score' => 0,
            'yellow_card' => 0,
            'match_status' =>'',
        ]);

        DB::table('bet')->insert([
            'user_id' => 1,
            'match_id' => 410600,
            'bet_type' => 1,
            'bet_amount' => 10000,
            'bet_content' => '0-1',
            'bet_time' => \Illuminate\Support\Facades\Date::now(),
            'bet_status' => '',
            'bet_gain' => 0,
        ]);
    }
}
