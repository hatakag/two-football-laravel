<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

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
        $this->get3rdAPIData(128);//128//
    }

    public function get3rdAPIData($league_id) {

        $ACTION = 'get_events';
        $API_KEY = '6b15223e25e9784070b71f3a43b0ae08870adb4b6a3e8453080d2b68c6d15bcb';

        $fromDate = date('Y-m-d', strtotime("-3 days")); //string
        $toDate = date('Y-m-d', strtotime("+8 days"));
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
            $matchList = json_decode($result, true);
            //print_r($matchList);
            //print($matchList[0]['match_id']);
            return $matchList;
        } catch (GuzzleException $e) {
            print($e->getMessage());
            return 0;
        }
    }
}
