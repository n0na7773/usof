<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Post;

class PostCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    public function setup()
    {
        CRUD::setModel(\App\Models\Post::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/post');
        CRUD::setEntityNameStrings('post', 'posts');
    }

    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('title');
        CRUD::column('content');
    }

    protected function setupShowOperation()
    {
        CRUD::column('id');
        CRUD::column('title');
        CRUD::column('content');
        CRUD::column('status');
        $post = CRUD::getCurrentEntry();

        $category_ids =\DB::table("category_post")->where("post_id", $post->id)->pluck("category_id");
        $categories = [];
        foreach($category_ids as $category_id){
            $category = \DB::table("categories")->where("id", $category_id)->pluck("title");
            array_push($categories, $category[0]);
        }
        $post->categories = $categories;

        $plus = \DB::table("likes")->where("post_id", $post->id)->where("type", 'like')->count();
        $minus = \DB::table("likes")->where("post_id", $post->id)->where("type", 'dislike')->count();
        $post->likes = $plus - $minus;

        $sub_ids =\DB::table("subscriptions")->where("post_id", $post->id)->pluck("user_id");
        $subs = [];
        foreach($sub_ids as $sub_id){
            $sub = \DB::table("users")->where("id", $sub_id)->pluck("full_name");
            array_push($subs, $sub[0]);
        }
        $post->subs = $subs;

        CRUD::addColumn([
            'name' => 'user_id',
            'label' => 'User id',
            'type' => 'text'
        ]);
        CRUD::column('user');
        CRUD::column('categories');
        CRUD::modifyColumn('categories', [
            'name' => 'categories',
            'label' => 'Categories',
            'type'  => 'array'
        ]);
        CRUD::column('likes');
        CRUD::modifyColumn('likes', [
            'name' => 'likes',
            'label' => 'Rating',
            'type' => 'integer'
        ]);
        CRUD::column('subs');
        CRUD::modifyColumn('subs', [
            'name' => 'subs',
            'label' => 'Subscribers',
            'type' => 'array',
        ]);
        CRUD::column('created_at');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(PostRequest::class);

        CRUD::field('user_id');
        CRUD::field('title');
        CRUD::field('content');
        CRUD::field('categories');
        CRUD::modifyField('categories', [
            'label'     => "Category",
            'type'      => 'select2_multiple',
            'name'      => 'category_id',
            'entity'    => 'category',
            'model'     => "App\Models\Category",
            'attribute' => 'title'
        ]);
    }

    protected function setupUpdateOperation()
    {
        CRUD::field('status');
        CRUD::modifyField('status', [
            'type' => 'enum',
        ]);
        CRUD::field('categories');
        CRUD::modifyField('categories', [
            'label'     => "Category",
            'type'      => 'select2_multiple',
            'name'      => 'category_id',
            'entity'    => 'category',
            'model'     => "App\Models\Category",
            'attribute' => 'title'
        ]);
    }

    public function store() {
        $post = Post::create(request()->all());
        $post->category()->attach(request()->category_id);

        return redirect('/admin/post/');
    }

    public function update()
    {
        request()->validate([
            'status' => 'in:active,inactive'
        ]);

        $post = CRUD::getCurrentEntry();
        $response = $this->traitUpdate();
        $post->update(request()->all());;
        if(request()->category_id){
            \DB::table('category_post')->where('post_id', $post->id)->delete();
            $post->category()->attach(request()->category_id);
        }

        return $response;

    }
}
