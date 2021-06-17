<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LikeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Like;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;

/**
 * Class LikeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LikeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Like::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/like');
        CRUD::setEntityNameStrings('like', 'likes');
    }

    protected function setupListOperation()
    {
        CRUD::removeButton('delete');
        CRUD::removeButton('update');

        CRUD::column('id');
        CRUD::column('user_id');
    }

    protected function setupShowOperation()
    {
        CRUD::removeButton('delete');
        CRUD::removeButton('update');

        CRUD::column('id');
        CRUD::column('post_id');
        CRUD::addColumn([
            'name' => 'comment_id',
            'label' => 'Comment id',
            'type' => 'integer'
        ]);
        CRUD::column('user_id');
        CRUD::column('type');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(LikeRequest::class);

        CRUD::removeButton('delete');
        CRUD::removeButton('update');

        CRUD::field('type');
        CRUD::modifyField('type', [
            'type' => 'enum',
        ]);
        CRUD::addField([
            'name' => 'post_id',
            'label' => 'Post id',
            'type' => 'text'
        ]);
        CRUD::addField([
            'name' => 'comment_id',
            'label' => 'Comment id',
            'type' => 'text'
        ]);

    }

    protected function setupUpdateOperation()
    {
        CRUD::removeButton('delete');
        CRUD::removeButton('update');
    }

    public function store()
    {

        $isPost = true;
        if (!request()->post_id)
            $isPost = false;

        $author_id = backpack_user()->id;

        if ($isPost) $post = Post::find(request()->post_id);
        else $comment = Comment::find(request()->comment_id);

        if ($isPost) $user_id = \DB::table('posts')->where('id', $post->id)->value("user_id");
        else $user_id = \DB::table('comments')->where('id', $comment->id)->value("user_id");
        $user = User::find($user_id);

        if ($isPost) $liked = \DB::table('likes')->where('post_id', $post->id)->where('user_id', $author_id)->get();
        else $liked = \DB::table('likes')->where('comment_id', $comment->id)->where('user_id', $author_id)->get();
        if($liked != "[]") $exist_like = Like::find($liked[0]->id);

        $like = new Like;
        $like->type = request()->type;

        if($liked == "[]") {
            $like->user()->associate($author_id);
            if ($isPost) $like->post()->associate($post->id);
            else $like->comment()->associate($comment->id);
            $like->save();
            if ($like->type == 'like') {
                if ($isPost) $post->rating += 1;
                else $comment->rating += 1;
                $user->rating += 1;
            }
            else {
                if ($isPost) $post->rating -= 1;
                else $comment->rating -= 1;
                $user->rating -= 1;
            }
        }
        else if ($exist_like->type == "like"){
            if ($like->type == 'like') {
                if ($isPost) $post->rating -= 1;
                else $comment->rating -= 1;
                $user->rating -= 1;
                if ($isPost) \DB::table('likes')->where('post_id', $post->id)->where('user_id', $author_id)->delete();
                else \DB::table('likes')->where('comment_id', $comment->id)->where('user_id', $author_id)->delete();
            }
            else {
                if ($isPost) $post->rating -= 2;
                else $comment->rating -= 2;
                $user->rating -= 2;
                $exist_like->type = 'dislike';
                $exist_like->save();
            }
        }
        else {
            if ($like->type == 'like') {
                if ($isPost) $post->rating += 2;
                else $comment->rating += 2;
                $user->rating += 2;
                $exist_like->type = 'like';
                $exist_like->save();
            }
            else {
                if ($isPost) $post->rating += 1;
                else $comment->rating += 1;
                $user->rating += 1;
                if ($isPost)\DB::table('likes')->where('post_id', $post->id)->where('user_id', $author_id)->delete();
                else \DB::table('likes')->where('comment_id', $comment->id)->where('user_id', $author_id)->delete();
            }
        }

        if ($isPost) $post->save();
        else $comment->save();
        $user->save();

        return $msg;
        return redirect('/admin/like/');
    }


}
