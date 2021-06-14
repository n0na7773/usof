<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create([
            'login' => $request->input('login'),
            'password' => Hash::make($request->input('password')),
            'full_name' => 'anonym',
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
        $remember_token = explode(".", $token)[2];
        $user = JWTAuth::user();
        $user->remember_token = $remember_token;
        $user->save();
        return response([
            'message' => 'Logged in',
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $user = $this->getUser($request);

        \Auth::guard('api')->logout();
        if($user) {
            $user->remember_token = "";
            $user->save();
            return response([
                'message' => 'Logged out'
            ]);
        }
        return response([
            'message' => 'Not logged out'
        ]);

    }

    public function reset_password(Request $request)
    {
        $user = \DB::table('users')->where('email', '=', $request->email)->first();

        \DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => Str::random(20)
        ]);
        $tokenData = \DB::table('password_resets')->where('email', $request->email)->first();

        $link = 'http://127.0.0.1:8000/api/auth/password-reset/' . $tokenData->token;

        $data = [
            'login' => $user->login,
            'link' => $link
        ];

        Mail::send('reset_password', $data, function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Reset password at usof');
        });
        return "Password reset link sent";
    }

    public function confirm_token(Request $request, $id)
    {
        $tokenData = \DB::table('password_resets')->where('token', $request->token)->first();

        if (!$tokenData) return "Wrong token";

        $user = User::where('email', $tokenData->email)->first();

        if (!$user) return "Wrong email";

        $user->password = \Hash::make($request->input('password'));
        $user->update();

        $user = JWTAuth::user();

        \DB::table('password_resets')->where('email', $tokenData->email)->delete();

        return response([
            'message' => 'Password changed'
        ]);
    }
}
