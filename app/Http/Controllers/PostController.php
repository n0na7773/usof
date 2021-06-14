<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::all();
        foreach($posts as $post) {
            $post->categories = \DB::table('category_post')->where('post_id', $post->id)->pluck("category_id");
        }

        if($request['sort'] == 'rating') {
            if ($request['order'] == 'desc') $sorted = $posts->sortByDesc('rating');
            else $sorted = $posts->sortBy('rating');
        }
        else if ($request['sort'] == 'date') {
            if ($request['order'] == 'desc')$sorted = $posts->sortByDesc('updated_at');
            else $sorted = $posts->sortBy('updated_at');
        }
        else $sorted = $posts;
        if ($request['filter'] == 'category'){
            $filtered = $sorted->filter(function ($value, $key) use ($request) {
                foreach($value->categories as $category)
                if ($category == $request['category'])
                    return $value->categories;
            });
        }
        else if ($request['filter'] == 'status'){
            $filtered = $sorted->filter(function ($value, $key) use ($request) {
                if ($value->status == $request['status'])
                    return $value;
            });
        }
        else if ($request['filter'] == 'date'){
            $filtered = $sorted->whereBetween('updated_at', [$request['dateFrom'], $request['dateTo']])->all();
        }
        else $filtered = $sorted;
        return $filtered;
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
        if(!$this->isLogged($request)){
            return response([
                'message' => 'You have no auth'
            ], 401);
        }
        $post = Post::find($id);
        if(!$this->isAdmin($request) && $this->getUser($request)->id != $post->user_id){
            return response([
                'message' => 'You have no rights'
            ], 401);
        }

        $post->update($request->all());
        if($request["categories"]){
            \DB::table('category_post')->where('post_id', $id)->delete();
            $post->category()->attach($request['categories']);
        }
        if($request["categories"] == []){
            \DB::table('category_post')->where('post_id', $id)->delete();
        }
        $post->categories = \DB::table('category_post')->where('post_id', $id)->pluck("category_id");

        $sub_ids = \DB::table('subscriptions')->where('post_id', $id)->pluck("user_id");
        foreach($sub_ids as $sub_id) {
            $sub = User::find($sub_id);
            $this->send_notification($sub, $id);
        }

        return $post;
    }

    public function destroy(Request $request, $id)
    {
        if(!$this->isLogged($request)){
            return response([
                'message' => 'You have no auth'
            ], 401);
        }
        $post = Post::find($id);
        if(!$this->isAdmin($request) && $this->getUser($request)->id != $post->user_id){
            return response([
                'message' => 'You have no rights'
            ], 401);
        }
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

    public function get_comments(Request $request, $id)
    {
        $comments = \DB::table('comments')->where('post_id', $id)->get();
        if($request['sort'] == 'rating') {
            if ($request['order'] == 'desc') $sorted = $comments->sortByDesc('rating');
            else $sorted = $comments->sortBy('rating');
        }
        else if ($request['sort'] == 'date') {
            if ($request['order'] == 'desc')$sorted = $comments->sortByDesc('updated_at');
            else $sorted = $comments->sortBy('updated_at');
        }

        return $sorted;
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
        $post = Post::find($id);
        if(!$this->isAdmin($request) && $this->getUser($request)->id != $post->user_id){
            return response([
                'message' => 'You have no rights'
            ], 401);
        }

        $user = $this->getUser($request);
        $user_id = $user->id;

        $post = Post::find($id);
        $liked = \DB::table('likes')->where('post_id', $id)->get();
        if($liked != "[]") $exist_like = Like::find($liked[0]->id);

        $like = new Like;
        $like->type = $request['type'];

        if($liked == "[]") {
            $like->user()->associate($user_id);
            $like->post()->associate($post->id);
            $like->save();
            if ($like->type == 'like') {
                $post->rating += 1;
                $user->rating += 1;
            }
            else {
                $post->rating -= 1;
                $user->rating -= 1;
            }
            $del_message = 'Added';
        }
        else if ($exist_like->type == 'like'){
            if ($like->type == 'like') {
                $post->rating -= 1;
                $user->rating -= 1;
                $this->delete_post_like($request, $id);
                $del_message = 'Removed';
            }
            else {
                $post->rating -= 2;
                $user->rating -= 2;
                $exist_like->type = 'dislike';
                $exist_like->save();
                $del_message = 'Replaced';
            }
        }
        else {
            if ($like->type == 'like') {
                $post->rating += 2;
                $user->rating += 2;
                $exist_like->type = 'like';
                $exist_like->save();
                $del_message = 'Replaced';
            }
            else {
                $post->rating += 1;
                $user->rating += 1;
                $this->delete_post_like($request, $id);
                $del_message = 'Removed';
            }
        }

        $post->save();
        $user->save();

        return response([
            'message' => $del_message . '. It`s type: ' . $like->type,
            'post_rating'  => $post->rating,
            'user_rating' => $user->rating
        ]);
    }

    public function delete_post_like(Request $request, $id)
    {
        if(!$this->isLogged($request)){
            return response([
                'message' => 'You have no auth'
            ], 401);
        }
        $post = Post::find($id);
        if(!$this->isAdmin($request) && $this->getUser($request)->id != $post->user_id){
            return response([
                'message' => 'You have no rights'
            ], 401);
        }

        $user_id = $this->getUser($request)->id;
        $like_id = \DB::table('likes')->where('post_id', $id)->where('user_id', $user_id)->value('id');

        Like::destroy($like_id);
    }

    public function send_notification(User $user, $post_id)
    {
        $link = 'http://127.0.0.1:8000/api/posts/' . $post_id;

        $data = [
            'login' => $user->login,
            'link' => $link
        ];

        Mail::send('notification', $data, function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('New notification at usof');
        });
        return "Notification sent";
    }
}
