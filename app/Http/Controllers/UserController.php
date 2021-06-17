<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        // get all users
        return User::all();
    }

    public function store(Request $request)
    {
        if(!$this->isLogged($request)){
            return response([
                'message' => 'You have no auth'
            ], 401);
        }

        if(!$this->isAdmin($request)){
            return response([
                'message' => 'You have no rights'
            ], 401);
        }

        return User::create($request->all());
    }


    public function show($id)
    {
        return User::find($id);
    }

    public function update(Request $request, $id)
    {
        if(!$this->isLogged($request)){
            return response([
                'message' => 'You have no auth'
            ], 401);
        }
        if(!$this->isAdmin($request) && $this->getUser($request)->id != $id){
            return response([
                'message' => 'You have no rights'
            ], 401);
        }

        $user = User::find($id);
        $user->update($request->all());
        return $user;
    }

    public function destroy(Request $request, $id)
    {
        if(!$this->isLogged($request)){
            return response([
                'message' => 'You have no auth'
            ], 401);
        }
        if(!$this->isAdmin($request) && $this->getUser($request)->id != $id){
            return response([
                'message' => 'You have no rights'
            ], 401);
        }
        $this->destroy_avatar($id, 'png');
        $this->destroy_avatar($id, 'jpg');
        return User::destroy($id);
    }
    public function destroy_avatar($id, $extension)
    {
        Storage::delete('avatars/avatar' . $id . '.' . $extension);
    }

    public function upload_avatar(Request $request)
    {
        if(!$this->isLogged($request)){
            return response([
                'message' => 'You have no auth'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'image' => 'image|required|mimes:png,jpg,jpeg'
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Only png and jpg images are allowed'
            ], 400);
        }

        $user = $this->getUser($request);

        $extension = $request->file('image')->extension();

        if($extension == 'jpg') $this->destroy_avatar($user->id, 'png');
        if($extension == 'png') $this->destroy_avatar($user->id, 'jpg');

        $file = 'avatar' . $user->id . '.' . $extension;

        $request->file('image')->storeAs('/avatars', $file);

        $user->image = '/avatars/avatar' . $user->id . '.' . $extension;
        $user->save();
        return response([
            'message' => 'Avatar uploaded'
        ]);

    }
}
