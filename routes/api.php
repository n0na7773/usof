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


Route::prefix('auth')->group(function() {
    Route::post('/register', 'App\Http\Controllers\AuthController@register');
    Route::post('/login', 'App\Http\Controllers\AuthController@login');
    Route::post('/logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('/password-reset', 'App\Http\Controllers\AuthController@reset_password');
    Route::post('/password-reset/{token}', 'App\Http\Controllers\AuthController@confirm_token');
});


Route::post('/users/avatar', 'App\Http\Controllers\UserController@upload_avatar');
Route::get('/users/subscription', 'App\Http\Controllers\SubscriptionController@get_user_subscriptions');
Route::get('/users/favorite', 'App\Http\Controllers\FavoriteController@get_user_favorites');

Route::resource('users', 'App\Http\Controllers\UserController');

Route::resource('posts', 'App\Http\Controllers\PostController');

Route::prefix('posts')->group(function() {
    Route::get('/{post_id}/comments', 'App\Http\Controllers\PostController@get_comments');
    Route::get('/{post_id}/categories', 'App\Http\Controllers\PostController@get_post_categories');
    Route::get('/{post_id}/like', 'App\Http\Controllers\PostController@get_post_likes');
    Route::post('/{post_id}/comments', 'App\Http\Controllers\PostController@post_comment');
    Route::post('/{post_id}/like', 'App\Http\Controllers\PostController@post_post_like');
    Route::delete('/{post_id}/like', 'App\Http\Controllers\PostController@delete_post_like');

    Route::post('/{post_id}/subscription', 'App\Http\Controllers\SubscriptionController@post_user_subscription');
    Route::delete('/{post_id}/subscription', 'App\Http\Controllers\SubscriptionController@delete_user_subscription');

    Route::post('/{post_id}/favorite', 'App\Http\Controllers\FavoriteController@post_user_favorite');
    Route::delete('/{post_id}/favorite', 'App\Http\Controllers\FavoriteController@delete_user_favorite');
});

Route::resource('categories', 'App\Http\Controllers\CategoriesController');
Route::get('/categories/{category_id}/posts', 'App\Http\Controllers\CategoriesController@get_category_posts');

Route::resource('comments', 'App\Http\Controllers\CommentsController');
Route::get('/comments/{comment_id}/like', 'App\Http\Controllers\CommentsController@get_comment_likes');
Route::post('/comments/{comment_id}/like', 'App\Http\Controllers\CommentsController@post_comment_like');
Route::delete('/comments/{comment_id}/like', 'App\Http\Controllers\CommentsController@delete_comment_like');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
