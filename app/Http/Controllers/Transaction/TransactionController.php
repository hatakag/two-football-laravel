<?php

namespace App\Http\Controllers\Transaction;

use App\Models\Card;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    //
    public function deposit(Request $request, $user_id) {
        try {
            $user = User::findOrFail($user_id);
            if ($user_id != auth()->user()->user_id)
                return response()->json(config('constants.error_response.INVALID_TOKEN'), Response::HTTP_FORBIDDEN);

            $validator = Validator::make($request->all(), [
                'code' => ['required', 'string', 'regex:/^[a-zA-Z0-9]{3}$/'],
                'password' => ['required', 'string', 'min:1','max:600'],
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->messages(),
                    'code' => 100,
                ], Response::HTTP_BAD_REQUEST);
            }

            if (!Hash::check($request->get('password'), $user->password)) {
                //error_log('password');
                return response()->json(config('constants.error_response.BAD_CARD_REQUEST'), Response::HTTP_BAD_REQUEST);
            }
            try {
                $card = Card::findOrFail($request->get('code'));
            } catch (ModelNotFoundException $e) {
                ///error_log('card');
                return response()->json(config('constants.error_response.BAD_CARD_REQUEST'), Response::HTTP_BAD_REQUEST);
            }
            if ($card->active == false) {
                //error_log('card_active');
                return response()->json(config('constants.error_response.BAD_CARD_REQUEST'), Response::HTTP_BAD_REQUEST);
            }

            $user->increaseBalance($card->value);
            $card->active = false;
            $card->save();
            return response()->json([
                'status' => true,
                'bill' => [
                    'card_amount' => $card->value,
                    'previous_balance' => $user->balance-$card->value,
                    'balance' => $user->balance,
                    'deposit_time' => date('Y-m-dTH:i:s'),
                ],
            ], Response::HTTP_OK);

        } catch (ModelNotFoundException $e) {
            return response()->json(config('constants.error_response.USER_NOT_FOUND'), Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'code' => 100,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
