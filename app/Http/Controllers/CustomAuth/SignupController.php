<?php

namespace App\Http\Controllers\CustomAuth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class SignupController extends Controller
{
    //
    public function signup(Request $request) {

        $validator = Validator::make($request->all(), [
            'username' => ['required','string','max:100',Rule::unique('user')],
            'password'=> ['required','string','max:600'],
            'name' => ['required','string','max:60'],
            'phone' => ['required','string','max:11',Rule::unique('user')],
            'email' => ['required','string','email','max:100',Rule::unique('user')],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->messages(),
                'code' => 100,
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            User::create([
                'username' => $request->get('username'),
                'password' => bcrypt($request->get('password')),
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'email' => $request->get('email'),
            ]);
            $user = User::where('username', $request->get('username'))->firstOrFail();
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'code' => 100,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => true,
            'user' => $user,
        ], Response::HTTP_OK);
    }
}
