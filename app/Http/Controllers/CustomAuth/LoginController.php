<?php

namespace App\Http\Controllers\CustomAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    //
    public function login(Request $request) {
        $credentials = $request->only('username', 'password');
        $rules = [
            'username' => 'required|string|max:100',
            'password' => 'required|string|max:600',
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->messages(),
                'code' => 101,
            ]);
        }
        try {
            // Attempt to verify the credentials and create a token for the user
            if (! $token = auth()->attempt($credentials)) {
                return response()->json([
                    'status' => false,
                    'message' => 'We can`t find an account with this credentials.',
                    'code' => 101,
                ], 401);
            }
        } catch (JWTException $e) {
            // Something went wrong with JWT Auth.
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'code' => 101,
            ], 500);
        }
        // All good so return the token
        return response()->json([
            'status' => true,
            'access_token' => $token,
            'user' => auth()->user(),
        ]);
    }
}
