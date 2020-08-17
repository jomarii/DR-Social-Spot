<?php

namespace App\Http\Controllers\Api;

use App\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Comment as CommentResource;

class CommentController extends Controller
{
    public function getCommentsByPost($postId){
    	if(Auth::user()->tokenCan('post:list')){
	    	$comments = Comment::where('post_id', $postId)
	    					   ->whereNull('parent_id')
	    					   ->get();
	    	return CommentResource::collection($comments);
	    }

        abort(403, 'Unauthorized access');
    }
}
