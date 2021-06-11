<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return Post::all();
    }

    public function store(Request $request)
    {
        if(!$this->isLogged($request)){
            return response([
                'message' => 'You have no auth'
            ], 401);
        }

        $user_id = $this->getUser($request)->id;

        $post = new Post;
        $post->title = $request['title'];
        $post->content = $request['content'];
        $post->user()->associate($user_id);
        $post->save();
        $post->category()->attach($request['categories']);
        return $post;
    }


    public function show($id)
    {
        $post = Post::find($id);
        $post->categories = \DB::table('category_post')->where('category_id', $id)->value("category_id");
        return $post;
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        $post->update($request->all());
        return $post;
    }

    public function destroy($id)
    {
        return Post::destroy($id);
    }
}
