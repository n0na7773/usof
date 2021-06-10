<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        // get all users
        return Post::all();
    }

    public function store(Request $request)
    {
        // create a user
        return Post::create($request->all());
    }


    public function show($id)
    {
        // show a user
        return Post::find($id);
    }

    public function update(Request $request, $id)
    {
        // update a user
        $user = Post::find($id);
        $user->update($request->all());
        return $user;
    }

    public function destroy($id)
    {
        // delete a user
        return Post::destroy($id);
    }
}
