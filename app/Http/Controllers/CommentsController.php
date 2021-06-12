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
        $comment = Comment::find($id);
        $comment->update($request->all());
        return $comment;
    }

    public function destroy($id)
    {
        //
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
        //
        if(!$this->isLogged($request)){
            return response([
                'message' => 'You have no auth'
            ], 401);
        }

        $user = $this->getUser($request);
        $user_id = $user->id;

        $comment = Comment::find($id);
        $liked = \DB::table('likes')->where('comment_id', $id)->get();

        if($liked == "[]") {
            $user->rating += 1;
            $user->save();

            $like = new Like;
            $like->type = $request['type'];
            $like->user()->associate($user_id);
            $like->comment()->associate($comment->id);
            $like->save();
            return $like;
        }

        $user->rating -= 1;
        $user->save();

        $this->delete_comment_like($request, $id);

        return response([
            'message' => 'Like removed'
        ], 401);
    }

    public function delete_comment_like(Request $request, $id)
    {
        //
        $user_id = $this->getUser($request)->id;
        $like_id = \DB::table('likes')->where('comment_id', $id)->where('user_id', $user_id)->value('id');

        return Like::destroy($like_id);
    }
}
