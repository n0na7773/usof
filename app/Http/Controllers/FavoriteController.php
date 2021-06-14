<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function get_user_favorites(Request $request)
    {
        return \DB::table('favorites')->where('user_id', $this->getUser($request)->id)->get();
    }

    public function post_user_favorite(Request $request, $id)
    {
        if(!$this->isLogged($request)){
            return response([
                'message' => 'You have no auth'
            ], 401);
        }

        $user_id = $this->getUser($request)->id;

        $favs = \DB::table('favorites')->where('post_id', $id)->where('user_id', $user_id)->get();
        if($favs != "[]") $this->delete_user_favorite($request, $id);
        else if($favs == "[]") {
            $favorite = new Favorite;
            $favorite->user()->associate($user_id);
            $favorite->post()->associate($id);
            $favorite->save();
        }
        return $favorite;
    }

    public function delete_user_favorite(Request $request, $id)
    {
        if(!$this->isLogged($request)){
            return response([
                'message' => 'You have no auth'
            ], 401);
        }

        $user_id = $this->getUser($request)->id;

        $favs = \DB::table('favorites')->where('post_id', $id)->where('user_id', $user_id)->get();

        Favorite::destroy($favs[0]->id);
    }
}
