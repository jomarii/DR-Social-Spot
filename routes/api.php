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
Route::group(['namespace' => 'api'], function(){
	Route::post('/register', 'AuthenticationController@register');
	Route::post('/login', 'AuthenticationController@authenticate');

	//authenticated routes
	Route::group(['middleware' => 'auth:sanctum'], function(){

		//Post routes
		Route::get('/posts/{user_id}', 'PostController@getPostsByUser');
		Route::group(['prefix' => 'post'], function(){
			Route::post('/create', 'PostController@create');
			Route::put('/like/{post_id}', 'PostController@likePost');
			Route::post('/comment/{post_id}', 'PostController@comment');
			Route::post('/comment-reply/{post_id}/{comment_id}', 'PostController@commentReply');
		});

		//Profile routes
		Route::group(['prefix' => 'profile'], function(){
			Route::patch('/update', 'ProfileController@update');
		});

		//newsfeed
		Route::get('/newsfeed', 'PostController@getNewsfeedPosts');

		//logout
		Route::put('/logout', 'AuthenticationController@logout');
	});
});