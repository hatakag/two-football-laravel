<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //
    public function getUsers() {
        $users = User::all();
        return response()->json($users->toArray(), 200);
    }

    public function updateUser(Request $request, $user_id) {

    }
}
