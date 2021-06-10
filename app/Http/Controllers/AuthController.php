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
        /*$validated =  $request->validate([
            'login'=> 'required|string|unique:users,login',
            'password'=> 'required|confirmed|min:4',
            'full_name'=> 'required|string',
            'email'=> 'required|email|unique:users,email',
            'image' => 'required|string'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response([
            'message' => 'User registered. Please log in',
            'user' => $user
        ]);
*/
        $user = User::create([
            'login' => $request->input('login'),
            'password' => Hash::make($request->input('password')),
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'image' => 'image_path'
        ]);
        return $user;
    }

    public function login()
    {
        $credentials = request()->only(['login', 'password']);

        $token = JWTAuth::attempt($credentials);

        if ($token = JWTAuth::attempt($credentials)) {
            return response([
                'message' => 'Logged in',
                'token' => $token,
                'user' => JWTAuth::user()
            ]);
        } else {
            return response([
                'message' => 'Incorrect log in!'
                ], 400);
        }
    }

    public function logout()
    {
        //
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
