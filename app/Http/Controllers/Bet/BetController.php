<?php

namespace App\Http\Controllers\Bet;

use App\Models\Bet;
use App\Models\Fixture;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class BetController extends Controller
{
    //
    public function betMatch(Request $request,$match_id) {
        try {
            $match = Fixture::findOrFail($match_id);
            if ($match->match_status == 'FT') {
                return response()->json(config('constants.error_response.MATCH_FINISHED'), Response::HTTP_BAD_REQUEST);
            }
            if ($match->match_status != '') {
                return response()->json(config('constants.error_response.MATCH_LIVE'), Response::HTTP_BAD_REQUEST);
            }

            $user = auth()->user();

            $validator = Validator::make($request->all(), [
                'bet_type' => ['required','integer','between:1,3'],
                'bet_amount' => ['required','numeric', 'min:1'],
                'bet_content' => ['required', 'string','regex:/^([0-9]{1,2}-[0-9]{1,2}|[0-9])$/'],
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->messages(),
                    'code' => 100,
                ], Response::HTTP_BAD_REQUEST);
            }

            if ($user->balance < $request->get("bet_amount")) {
                return response()->json(config('constants.error_response.INVALID_BET_AMOUNT'), Response::HTTP_BAD_REQUEST);
            }

            $betRecord = Bet::where('user_id', $user->user_id)
                ->where('match_id', $match_id)
                ->where('bet_type', $request->get("bet_type"))
                ->first();
            if (!is_null($betRecord)) {
                return response()->json(config('constants.error_response.BET_ALREADY'), Response::HTTP_BAD_REQUEST);
            }
            $betTime = Date::now();
            $betRecord = Bet::create([
                'user_id' => $user->user_id,
                'match_id' => $match_id,
                'bet_type' => $request->get("bet_type"),
                'bet_amount' => $request->get("bet_amount"),
                'bet_content' => $request->get("bet_content"),
                'bet_time' => $betTime,
            ]);
            $user->decreaseBalance($request->get("bet_amount"));

            return response()->json([
                'status' => true,
                'bet' => [
                    'user_id' => $user->user_id,
                    'match_id' => $match_id,
                    'bet_type' => $request->get("bet_type"),
                    'bet_amount' => $request->get("bet_amount"),
                    'bet_content' => $request->get("bet_content"),
                    'bet_time' => date('Y-m-d\TH:i:s', strtotime($betTime)),
                    'bet_status' => 'PROCESSING',
                ]
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(config('constants.error_response.MATCH_NOT_FOUND'), Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'code' => 100,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUserBetsForMatch($match_id)
    {
        try {
            $match = Fixture::findOrFail($match_id);
            $bets = Bet::where('match_id', $match_id)->where('user_id', auth()->user()->user_id)->get();
            foreach ($bets as $bet) {
                $bet->bet_time = date('Y-m-d\TH:i:s', strtotime($bet->bet_time));
            }
            return response()->json([
                'status' => true,
                'bets' => $bets,
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(config('constants.error_response.MATCH_NOT_FOUND'), Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'code' => 100,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getBets($user_id)
    {
        try {
            if ($user_id != auth()->user()->user_id)
                return response()->json(config('constants.error_response.INVALID_TOKEN'), Response::HTTP_FORBIDDEN);
            $bets = Bet::where('user_id', $user_id)->get();
            $betsRes = array();
            foreach ($bets as $bet) {
                $bet['bet_time'] = date('Y-m-d\TH:i:s', strtotime($bet['bet_time']));
                $bet['match'] = Fixture::find($bet['match_id']);
                unset($bet['match_id']);
                array_push($betsRes, $bet);
            }
            return response()->json([
                'status' => true,
                'bets' => $betsRes,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'code' => 100,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
