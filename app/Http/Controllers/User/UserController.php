<?php

namespace App\Http\Controllers\User;

use App\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use test\Mockery\ReturnTypeObjectTypeHint;

class UserController extends Controller
{
    //
    public function getUsers(Request $request) {
        try {
            $users = User::all();
            return response()->json([
                'status' => true,
                'users' => $users->toArray()
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'code' => 101,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateUser(Request $request, $user_id) {
        try {
            $user = User::findOrFail($user_id);

            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:100|unique:user,'.$user_id,
                'name' => 'required|string|max:60',
                'picture' => '',
                'phone' => 'required|string|max:11|unique:user,'.$user_id,
                'email' => 'required|string|email|max:100|unique:user,'.$user_id,
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->messages(),
                    'code' => 101,
                ], Response::HTTP_BAD_REQUEST);
            }

            $user->username = $request->get('username');
            $user->name = $request->get('name');
            $user->picture = $request->get('picture');
            $user->phone = $request->get('phone');
            $user->email = $request->get('email');
            $user->save();
            return response()->json([
               'status' => true,
               'user' => $user,
            ], Response::HTTP_OK);

        } catch (ModelNotFoundException $e) {
            return response()->json(config('constants.error_response.USER_NOT_FOUND'), Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'code' => 101,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
