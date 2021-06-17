<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CommentRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Comment;

class CommentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }

    public function setup()
    {
        CRUD::setModel(\App\Models\Comment::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/comment');
        CRUD::setEntityNameStrings('comment', 'comments');
    }

    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('user_id');
        CRUD::column('post_id');
        CRUD::column('content');
    }

    protected function setupShowOperation() {
        $comment = CRUD::getCurrentEntry();
        $plus = \DB::table("likes")->where("comment_id", $comment->id)->where("type", 'like')->count();
        $minus = \DB::table("likes")->where("comment_id", $comment->id)->where("type", 'dislike')->count();
        $comment->likes = $plus - $minus;
        CRUD::column('id');
        CRUD::addColumn([
            'name' => 'user_id',
            'label' => 'Author id',
            'type' => 'text'
        ]);
        CRUD::column('user');
        CRUD::column('post_id');
        CRUD::column('content');
        CRUD::column('status');
        CRUD::column('likes');
        CRUD::modifyColumn('likes', [
            'label' => 'Rating',
            'type' => 'integer',
            'name' => 'likes',
        ]);
        CRUD::column('created_at');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(CommentRequest::class);

        CRUD::field('user_id');
        CRUD::field('post_id');
        CRUD::field('content');
        CRUD::field('status');
        CRUD::modifyField('status', [
            'type' => 'enum',
        ]);
    }

    protected function setupUpdateOperation()
    {
        CRUD::field('status');
        CRUD::modifyField('status', [
            'type' => 'enum',
        ]);
    }
}
