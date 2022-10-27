<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            $status = false;
            $response = ['status' => $status, 'message' => $validator->errors()->all()];
            return response($response, 200);
        }
        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $user = User::create($request->toArray());
        $status = true;
        $response = ['status' => $status, 'user' => $user, 'message' => 'You have successfully registered!'];
        return response($response, 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            $status = false;
            $response = ['status' => $status, 'message' => $validator->errors()->all()];
            return response($response, 200);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Password Grant Client')->accessToken;
                $status = true;
                $response = ['status' => $status, 'token' => $token, 'user' => $user, 'message' => 'You have successfully logged in!'];
                return response($response, 200);
            } else {
                $status = false;
                $response = ['status' => $status, "message" => "Password mismatch"];
                return response($response);
            }
        } else {
            $status = false;
            $response = ['status' => $status, "message" => 'User does not exist'];
            return response($response);
        }
    }
}
