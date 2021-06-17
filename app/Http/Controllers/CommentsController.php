<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Like;

class CommentsController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
        return Comment::find($id);
    }

    public function update(Request $request, $id)
    {
        //
        if(!$this->isLogged($request)){
            return response([
                'message' => 'You have no auth'
            ], 401);
        }

        $comment = Comment::find($id);

        if(!$this->isAdmin($request) && $this->getUser($request)->id != $comment->user_id){
            return response([
                'message' => 'You have no rights'
            ], 401);
        }

        $comment->update($request->all());
        return $comment;
    }

    public function destroy(Request $request, $id)
    {
        if(!$this->isLogged($request)){
            return response([
                'message' => 'You have no auth'
            ], 401);
        }
        $comment = Comment::find($id);
        if(!$this->isAdmin($request) && $this->getUser($request)->id != $comment->user_id){
            return response([
                'message' => 'You have no rights'
            ], 401);
        }
        return Comment::destroy($id);
    }

    public function get_comment_likes($id)
    {
        //
        $likes = \DB::table('likes')->where('comment_id', $id)->get();

        return $likes;
    }

    public function post_comment_like(Request $request, $id)
    {
        if(!$this->isLogged($request)){
            return response([
                'message' => 'You have no auth'
            ], 401);
        }

        $comment = Comment::find($id);

        $author = $this->getUser($request);
        $author_id = $author->id;

        $user_id = \DB::table('comments')->where('id', $comment->id)->value("user_id");
        $user = User::find($user_id);

        $liked = \DB::table('likes')->where('comment_id', $id)->where('user_id', $author_id)->get();
        if($liked != "[]") $exist_like = Like::find($liked[0]->id);

        $like = new Like;
        $like->type = $request['type'];

        if($liked == "[]") {
            $like->user()->associate($author_id);
            $like->comment()->associate($comment->id);
            $like->save();
            if ($like->type == 'like') {
                $comment->rating += 1;
                $user->rating += 1;
            }
            else {
                $comment->rating -= 1;
                $user->rating -= 1;
            }
            $del_message = 'Added';
        }
        else if ($exist_like->type == 'like'){
            if ($like->type == 'like') {
                $comment->rating -= 1;
                $user->rating -= 1;
                $this->delete_post_like($request, $id);
                $del_message = 'Removed';
            }
            else {
                $comment->rating -= 2;
                $user->rating -= 2;
                $exist_like->type = 'dislike';
                $exist_like->save();
                $del_message = 'Replaced';
            }
        }
        else {
            if ($like->type == 'like') {
                $comment->rating += 2;
                $user->rating += 2;
                $exist_like->type = 'like';
                $exist_like->save();
                $del_message = 'Replaced';
            }
            else {
                $comment->rating += 1;
                $user->rating += 1;
                $this->delete_post_like($request, $id);
                $del_message = 'Removed';
            }
        }

        $comment->save();
        $author->save();
        $user->save();

        return response([
            'message' => $del_message . '. It`s type: ' . $like->type,
            'comment_rating'  => $comment->rating,
            'user_rating' => $user->rating
        ]);
    }

    public function delete_comment_like(Request $request, $id)
    {
        if(!$this->isLogged($request)){
            return response([
                'message' => 'You have no auth'
            ], 401);
        }
        $like = Like::find($id);
        if(!$this->isAdmin($request) && $this->getUser($request)->id != $like->user_id){
            return response([
                'message' => 'You have no rights'
            ], 401);
        }

        $user_id = $this->getUser($request)->id;
        $like_id = \DB::table('likes')->where('comment_id', $id)->where('user_id', $user_id)->value('id');

        Like::destroy($like_id);
    }
}
