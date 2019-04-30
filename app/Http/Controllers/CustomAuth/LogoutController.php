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
        try {
            auth()->logout();
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully',
                'code' => 100,
            ], Response::HTTP_OK);
        } catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'code' => 100,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
