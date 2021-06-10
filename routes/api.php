<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
CRUD is:
1. get all (GET) /api/posts
2. create a post (POST) /api/posts
3. get a single (GET) /api/posts/{id}
4. update a single (PUT/PATCH) /api/posts/{id}
5. delete (DELETE) /api/posts/{id}
*/

/*
to create a resource (posts) in laravel
1. create the database and migrations
2. create a model
2.5. create a service? Eloquent ORM
3. create a controller to go get info from database
4. return that info
*/


Route::resource('users', 'App\Http\Controllers\UserController');

Route::prefix('auth')->group(function() {
    Route::post('/register', 'App\Http\Controllers\AuthController@register');
    Route::post('/login', 'App\Http\Controllers\AuthController@login');
    /*Route::post('/logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('/password-reset', 'App\Http\Controllers\AuthController@reset_password');
    Route::post('/password-reset/{token}', 'App\Http\Controllers\AuthController@confirm_token');*/
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
