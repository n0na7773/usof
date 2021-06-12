<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;

class CategoriesController extends Controller
{
    public function index()
    {
        //
        return Category::all();
    }

    public function store(Request $request)
    {
        //
        return Category::create($request->all());
    }

    public function show($id)
    {
        //
        return Category::find($id);
    }

    public function update(Request $request, $id)
    {
        //
        $category = Category::find($id);
        $category->update($request->all());
        return $category;
    }

    public function destroy($id)
    {
        //
        return Category::destroy($id);
    }
    public function get_category_posts($id)
    {
        //
        $post_ids = \DB::table('category_post')->where('category_id', $id)->pluck('post_id');

        $posts = [];
        foreach($post_ids as $post_id){
            $post = Post::find($post_id);
            $post->categories = \DB::table('category_post')->where('post_id', $post_id)->pluck("category_id");
            array_push($posts, $post);
        }

        return $posts;
    }
}
