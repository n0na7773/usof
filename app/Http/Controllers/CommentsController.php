<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

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
}
