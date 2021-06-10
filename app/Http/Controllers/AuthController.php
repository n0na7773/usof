<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create([
            'login' => $request->input('login'),
            'password' => Hash::make($request->input('password')),
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'image' => 'image_path'
        ]);
        return response([
            'message' => 'Registered',
            'user' => $user
        ]);
    }

    public function login()
    {
        $credentials = request()->only(['login', 'password']);

        $token = JWTAuth::attempt($credentials);

        return response([
            'message' => 'Logged in',
            'token' => $token
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response([
            'message' => 'Logged out'
        ]);
    }

    public function reset_password()
    {
        //
    }

    public function confirm_token()
    {
        //
    }
}
