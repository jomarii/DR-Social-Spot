<?php

namespace App\Http\Controllers\Api;

use App\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Post as PostResource;

class PostController extends Controller
{
    public function create(Request $request){
    	if(Auth::user()->tokenCan('post:create')){
    		$request->validate([
	    		'post' => 'required|string|max:63206'
	    	]);

	    	$post = Post::create([
	    		'user_id' => Auth::user()->id,
	    		'post' => $request->post
	    	]);

	    	return response()->json([
	    		'message' => 'Posted Successfully!'
	    	]);
    	}

    	abort(403, 'Unauthorized access');
    }

    public function likePost($postId){
    	if(Auth::user()->tokenCan('post:like')){
    		$post = Post::findOrFail($postId);
    		$post->likes()->attach(Auth::user()->id);

    		return response()->json([
    			'message' => 'Liked!'
    		]);
    	}

    	abort(403, 'Unauthorized access');
    }
}
