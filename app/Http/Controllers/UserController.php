<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        // get all users
        return User::all();
    }

    public function store(Request $request)
    {
        // create a user
        return User::create($request->all());
    }


    public function show($id)
    {
        // show a user
        return User::find($id);
    }

    public function update(Request $request, $id)
    {
        // update a user
        $user = User::find($id);
        $user->update($request->all());
        return $user;
    }

    public function destroy($id)
    {
        // delete a user
        return User::destroy($id);
    }
}
