<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        foreach($posts as $post) {
            $post->categories = \DB::table('category_post')->where('post_id', $post->id)->pluck("category_id");
        }
        return $posts;
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
        $post->categories = \DB::table('category_post')->where('post_id', $id)->pluck("category_id");
        return $post;
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        $post->update($request->all());
        if($request["categories"]){
            \DB::table('category_post')->where('post_id', $id)->delete();
            $post->category()->attach($request['categories']);
        }
        if($request["categories"] == []){
            \DB::table('category_post')->where('post_id', $id)->delete();
        }
        $post->categories = \DB::table('category_post')->where('post_id', $id)->pluck("category_id");
        return $post;
    }

    public function destroy($id)
    {
        return Post::destroy($id);
    }

    public function get_post_categories($id)
    {
        //
        $category_ids = \DB::table('category_post')->where('post_id', $id)->pluck('category_id');

        $categories = [];
        foreach($category_ids as $category_id){
            $category = Category::find($category_id);
            array_push($categories, $category);
        }

        return $categories;
    }

    public function post_comment(Request $request, $id)
    {
        if(!$this->isLogged($request)){
            return response([
                'message' => 'You have no auth'
            ], 401);
        }

        $user_id = $this->getUser($request)->id;

        $post = Post::find($id);

        $comment = new Comment;
        $comment->content = $request['content'];
        $comment->user()->associate($user_id);
        $comment->post()->associate($post->id);
        $comment->save();

        return $comment;
    }

    public function get_comments($id)
    {
        $comments = \DB::table('comments')->where('post_id', $id)->get();

        return $comments;
    }

    public function get_post_likes($id)
    {
        $likes = \DB::table('likes')->where('post_id', $id)->get();

        return $likes;
    }

    public function post_post_like(Request $request, $id)
    {
        if(!$this->isLogged($request)){
            return response([
                'message' => 'You have no auth'
            ], 401);
        }
        $user = $this->getUser($request);
        $user_id = $user->id;

        $post = Post::find($id);
        $liked = \DB::table('likes')->where('post_id', $id)->get();
        if($liked == "[]") {
            $post->rating += 1;
            $post->save();

            $user->rating += 1;
            $user->save();

            $like = new Like;
            $like->type = $request['type'];
            $like->user()->associate($user_id);
            $like->post()->associate($post->id);
            $like->save();
            return $like;
        }

        $post->rating -= 1;
        $post->save();

        $user->rating -= 1;
        $user->save();

        $this->delete_post_like($request, $id);

        return response([
            'message' => 'Like removed'
        ], 401);
    }

    public function delete_post_like(Request $request, $id)
    {
        $user_id = $this->getUser($request)->id;
        $like_id = \DB::table('likes')->where('post_id', $id)->where('user_id', $user_id)->value('id');

        return Like::destroy($like_id);
    }
}
