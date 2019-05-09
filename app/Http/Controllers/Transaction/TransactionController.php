<?php

namespace App\Http\Controllers\Transaction;

use App\Models\Card;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    //
    public function deposit(Request $request, $user_id) {
        try {
            $user = User::findOrFail($user_id);
            $validator = Validator::make($request->all(), [
                'code' => ['required', 'string'],
                'password' => ['required', 'string', 'max:600'],
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->messages(),
                    'code' => 100,
                ], Response::HTTP_BAD_REQUEST);
            }

            if (bcrypt($request->get('password')) != $user->password) {
                return response()->json(config('constants.error_response.BAD_CARD_REQUEST'), Response::HTTP_BAD_REQUEST);
            }

            $card = Card::findOrFail($request->get('code'));
            if ($card->active == false) {
                return response()->json(config('constants.error_response.BAD_CARD_REQUEST'), Response::HTTP_BAD_REQUEST);
            }

            $user->balance += $card->value;
            $card->active = false;
            $card->save();
            $user->save();
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
