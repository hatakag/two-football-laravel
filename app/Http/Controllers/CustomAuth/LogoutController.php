<?php

namespace App\Http\Controllers\CustomAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;

class LogoutController extends Controller
{
    //
    public function logout(Request $request) {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            auth()->invalidate();

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully',
                'code' => 123,
            ], Response::HTTP_OK);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out',
                'code' => 123,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
