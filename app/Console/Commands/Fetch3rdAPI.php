<?php

namespace App\Console\Commands;

use App\Models\Bet;
use App\Models\Fixture;
use App\Models\League;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Pusher\Pusher;

class Fetch3rdAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:fetch3rdapi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get 3rd API data and update database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        print("Fetch 3rd API data\n");
        //$this->get3rdAPIData(128);//128//63
        $leagues = League::all();
        foreach ($leagues as $league) {
            $matchList = $this->get3rdAPIData($league->league_id);
            if ($matchList == 0) {
                print ("Cannot get 3rd API data\n");
                continue;
            }
            foreach ($matchList as $match) {
                print ($match->match_id);
                $matchID = $match->match_id;
                $leagueID = $match->league_id;
                if ($match->match_status == '') {
                    $hometeamHalftimeScore = 0;
                    $awayteamHalftimeScore = 0;
                    $hometeamScore = 0;
                    $awayteamScore = 0;
                    $yellowCard = 0;
                }
                else {
                    if ($match->match_hometeam_halftime_score != '')
                        $hometeamHalftimeScore = (int)$match->match_hometeam_halftime_score;
                    if ($match->match_awayteam_halftime_score != '')
                        $awayteamHalftimeScore = (int)$match->match_awayteam_halftime_score;
                    if ($match->match_hometeam_score != '')
                        $hometeamScore = (int)$match->match_hometeam_score;
                    if ($match->match_awayteam_score != '')
                        $awayteamScore = (int)$match->match_awayteam_score;

                    $statistics = $match->statistics;
                    $fullTime = false;
                    foreach ($statistics as $statistic) {
                        if ($statistic->type == 'yellow cards') {
                            $yellowCard = (int)$statistic->home + (int)$statistic->away;
                            $fullTime = true;
                            break;
                        }
                    }
                    if ($fullTime == false)
                        $yellowCard = 0;
                }
                /*
                print ($hometeamHalftimeScore." - ".$awayteamHalftimeScore);
                print ("\n");
                print ($hometeamScore." - ".$awayteamScore);
                print ("\n");
                print ($yellowCard);
                print ("\n");
                */
                try {
                    $matchRecord = Fixture::findOrFail($matchID);
                    $matchStatusBefore = $matchRecord->match_status;
                    //update match
                    if ($match->match_status != '' and $matchStatusBefore != 'FT') {
                        $matchRecord->match_hometeam_halftime_score = $hometeamHalftimeScore;
                        $matchRecord->match_awayteam_halftime_score = $awayteamHalftimeScore;
                        $matchRecord->match_hometeam_score = $hometeamScore;
                        $matchRecord->match_awayteam_score = $awayteamScore;
                        $matchRecord->yellow_card = $yellowCard;
                        $matchRecord->match_status = $match->match_status;
                        $matchRecord->save();
                    }

                    if ($match->match_status == 'FT' and $matchStatusBefore != 'FT') {
                        //calculate bet
                        $betList = Bet::where('match_id', $matchID)->get();
                        foreach ($betList as $bet) {
                            if ($bet->bet_status == 'PROCESSING') {
                                print($bet."\n");
                                //halftime bet
                                if ($bet->bet_type == 1) {
                                    $predictResult = explode("-", $bet->bet_content);
                                    if ((int)$predictResult[0] == $matchRecord->match_hometeam_halftime_score and (int)$predictResult[1] == $matchRecord->match_awayteam_halftime_score) {
                                        $user = User::find($bet->user_id);
                                        $betGain = $bet->bet_amount * 2;
                                        $user->balance += $betGain;
                                        $user->save();
                                        $bet->end($betGain);
                                    } else {
                                        $bet->end(0);
                                    }
                                }
                                //fulltime bet
                                if ($bet->bet_type == 2) {
                                    $predictResult = explode("-", $bet->bet_content);
                                    if ((int)$predictResult[0] == $matchRecord->match_hometeam_score and (int)$predictResult[1] == $matchRecord->match_awayteam_score) {
                                        $user = User::find($bet->user_id);
                                        $betGain = $bet->bet_amount * 3;
                                        $user->balance += $betGain;
                                        $user->save();
                                        $bet->end($betGain);
                                    } else {
                                        $bet->end(0);
                                    }
                                }
                                //yellow card bet
                                if ($bet->bet_type == 3) {
                                    if ((int)$bet->bet_content == $matchRecord->yellow_card) {
                                        $user = User::find($bet->user_id);
                                        $betGain = $bet->bet_amount * 2;
                                        $user->balance += $betGain;
                                        $user->save();
                                        $bet->end($betGain);
                                    } else {
                                        $bet->end(0);
                                    }
                                }
                                //pusher
                                $options = array(
                                    'cluster' => 'ap1',
                                    'useTLS' => true
                                );

                                $pusher = new Pusher(
                                    env('PUSHER_APP_KEY'),
                                    env('PUSHER_APP_SECRET'),
                                    env('PUSHER_APP_ID'),
                                    $options
                                );

                                $event = config("constants.pusher.BET_EVENT_PREFIX") . (string)$matchID . "_" . (string)$bet->user_id;
                                $data = [
                                    'match' => $matchRecord,
                                    'bet_type' => $bet->bet_type,
                                    'bet_amount' => $bet->bet_amount,
                                    'bet_content' => $bet->bet_content,
                                    'bet_time' => date("Y-m-dTH:i:s", strtotime($bet->bet_time)),
                                    'bet_status' => $bet->bet_status,
                                    'bet_gain' => $bet->bet_gain,
                                ];
                                $pusher->trigger(config("constants.pusher.BET_CHANNEL"), $event, $data);
                            }
                        }
                    }
                } catch (ModelNotFoundException $e) {
                    //insert match
                    $matchRecord = new Fixture();
                    $matchRecord->match_id = $matchID;
                    $matchRecord->league_id = $leagueID;
                    $matchRecord->match_date = $match->match_date;
                    $matchRecord->match_time = $match->match_time;
                    $matchRecord->match_hometeam_name = $match->match_hometeam_name;
                    $matchRecord->match_awayteam_name = $match->match_awayteam_name;
                    $matchRecord->match_hometeam_halftime_score = $hometeamHalftimeScore;
                    $matchRecord->match_awayteam_halftime_score = $awayteamHalftimeScore;
                    $matchRecord->match_hometeam_score = $hometeamScore;
                    $matchRecord->match_awayteam_score = $awayteamScore;
                    $matchRecord->yellow_card = $yellowCard;
                    $matchRecord->match_status = $match->match_status;
                    $matchRecord->save();
                }
            }
        }
    }

    public function get3rdAPIData($league_id) {

        $ACTION = 'get_events';
        $API_KEY = '6b15223e25e9784070b71f3a43b0ae08870adb4b6a3e8453080d2b68c6d15bcb';

//        $fromDate = date('Y-m-d', strtotime("-8 days")); //string
//        $toDate = date('Y-m-d', strtotime("+8 days"));

        $fromDate = '2019-05-10';
        $toDate = '2019-05-17';

        $param = [
            'action' => $ACTION,
            'from' => $fromDate,
            'to' => $toDate,
            'league_id' => $league_id,
            'APIkey' => $API_KEY,
        ];

        $client = new Client();
        try {
            $response = $client->request('GET', 'https://apifootball.com/api/', ['query' => $param]);
            $result = $response->getBody()->getContents();
            $matchList = json_decode($result); //json string to array of object
            //print_r($matchList);
            //print($matchList[0]->match_id);
            if (isset($matchList->error))
                return 0;
            return $matchList;
        } catch (\Exception $e) {
            print($e->getMessage());
            return 0;
        }
    }
}
