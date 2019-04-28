<?php

namespace App\Http\Controllers\User;

use App\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //
    public function getUsers(Request $request) {
        try {
            $users = User::all();
            return response()->json([
                'status' => 'success',
                'data' => $users->toArray()
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => 101,
            ], 500);
        }
    }

    public function updateUser(Request $request, $user_id) {

    }
}
