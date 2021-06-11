<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use App\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function isLogged(Request $request)
    {
        $token = explode(".", explode(" ", $request->header("Authorization"))[1])[2];
        $user = User::where("remember_token", $token)->first();
        if($user) return true;
        else return false;
    }

    public function isAdmin(Request $request)
    {
        $token = explode(".", explode(" ", $request->header("Authorization"))[1])[2];
        $user = User::where("remember_token", $token)->first();
        if($user->role == "admin") return true;
        else return false;
    }

    public function getUser(Request $request)
    {
        $token = explode(".", explode(" ", $request->header("Authorization"))[1])[2];
        $user = User::where("remember_token", $token)->first();
        return $user;
    }
}
