<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }

    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');
    }

    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('login');
        CRUD::column('full_name');
        CRUD::column('email');
    }

    protected function setupShowOperation() {
        $user = CRUD::getCurrentEntry();

        $fav_ids =\DB::table("favorites")->where("user_id", $user->id)->pluck("post_id");
        $favs = [];
        foreach($fav_ids as $fav_id){
            $fav = \DB::table("posts")->where("id", $fav_id)->pluck("title");
            array_push($favs, $fav[0]);
        }
        $user->favs = $favs;

        CRUD::column('id');
        CRUD::column('login');
        CRUD::column('full_name');
        CRUD::column('email');
        CRUD::column('rating');
        CRUD::column('role');
        CRUD::addColumn([
            'name' => 'image',
            'label' => 'Image',
            'type' => 'image',
            'width' => '200px',
            'height' => '200px'
        ]);
        CRUD::column('favs');
        CRUD::modifyColumn('favs', [
            'name' => 'favs',
            'label' => 'Favorites',
            'type' => 'array',
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(UserRequest::class);

        CRUD::field('login');
        CRUD::field('password');
        CRUD::field('password_confirmation');
        CRUD::field('email');
        CRUD::field('role');
        CRUD::modifyField('role', [
            'type' => 'enum',
        ]);
    }

    protected function setupUpdateOperation()
    {
        CRUD::field('full_name');
        //CRUD::field('password');
        //CRUD::field('password_confirmation');
        CRUD::field('role');
        CRUD::modifyField('role', [
            'type' => 'enum',
        ]);
    }

    public function store()
    {
        $request = request()->all();
        $request['password'] = Hash::make($request['password']);

        $user = User::create($request);

        return redirect('/admin/user/');
    }

    public function update()
    {
        request()->validate([
            'full_name'=> 'string',
            //'password'=> 'required|confirmed|min:4',
            //'password_confirmation'=> 'required',
            'role' => 'in:admin,user'
        ]);

        $user = CRUD::getCurrentEntry();
        $response = $this->traitUpdate();
        $user->update(request()->all());;
        return $response;
    }
}
