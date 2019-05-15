<?php

namespace App\Http\Controllers\Bet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class BetController extends Controller
{
    //
    public function betMatch() {

    }

    public function getUserBetsForMatch() {

    }

    public function getBets($user_id) {
        if ($user_id != auth()->user()->user_id)
            return response()->json(config('constants.error_response.INVALID_TOKEN'), Response::HTTP_FORBIDDEN);

    }
}
