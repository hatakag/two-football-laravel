<?php

namespace App\Http\Controllers\CustomAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    //
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('username', 'password');
            $rules = [
                'username' => ['required', 'string', 'max:100'],
                'password' => ['required', 'string', 'min:6', 'max:600'],
            ];
            $validator = Validator::make($credentials, $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->messages(),
                    'code' => 100,
                ], Response::HTTP_BAD_REQUEST);
            }
            try {
                // Attempt to verify the credentials and create a token for the user
                if (!$token = auth()->attempt($credentials)) {
                    return response()->json(config('constants.error_response.FAIL_LOGIN'), Response::HTTP_BAD_REQUEST);
                }
            } catch (JWTException $e) {
                // Something went wrong with JWT Auth.
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage(),
                    'code' => 100,
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            // All good so return the token
            return response()->json([
                'status' => true,
                'access_token' => $token,
                'user' => auth()->user(),
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
