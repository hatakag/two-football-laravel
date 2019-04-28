<?php

namespace App\Http\Controllers\CustomAuth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SignupController extends Controller
{
    //
    public function signup(Request $request) {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|unique:user',
            'username' => 'required|string|max:100|unique:user',
            'password'=> 'required|string|max:600',
            'name' => 'required|string|max:60',
            'phone' => 'required|string|max:11|unique:user',
            'email' => 'required|string|email|max:100|unique:user',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->messages(),
                'code' => 101,
            ]);
        }

        User::create([
            'user_id' => $request->get('user_id'),
            'username' => $request->get('username'),
            'password' => bcrypt($request->get('password')),
            'name' => $request->get('name'),
            'phone' => $request->get('phone'),
            'email' => $request->get('email'),
            'picture' => null,
            'role' => config('constants.role.user'),
            'balance' => 0,
        ]);

        $user = User::first();

        return response()->json([
            'status' => true,
            'user' => $user,
        ], 200);
    }
}
