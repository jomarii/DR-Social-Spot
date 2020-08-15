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

	Route::group(['middleware' => 'auth:sanctum'], function(){
		Route::group(['prefix' => 'post'], function(){
			Route::post('/create', 'PostController@create');
			Route::put('/like/{post_id}', 'PostController@likePost');
		});
	});
});