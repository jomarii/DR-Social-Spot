<?php

namespace App\Http\Controllers\Api;

use App\Post;
use App\User;
use App\Comment;
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

    //
    public function likePost($postId){
    	if(Auth::user()->tokenCan('post:like')){
    		$post = Post::findOrFail($postId);
            if($post->likes()->where('post_id', $postId)->where('user_id', Auth::user()->id)->exists()){
                $post->likes()->wherePivot('post_id', $postId)->detach(Auth::user()->id);
                $msg = 'Unliked!';
            }
            else{
                $post->likes()->attach(Auth::user()->id);
                $msg = 'Liked!';
            }
    		
    		return response()->json([
    			'message' => $msg
    		]);
    	}

    	abort(403, 'Unauthorized access');
    }

    public function comment(Request $request, $postId){
    	if(Auth::user()->tokenCan('post:comment')){
    		$request->validate([
    			'comment' => 'required|string|max:8000'
    		]);
    		$post = Post::findOrFail($postId);
    		$comment = Comment::create([
    			'post_id' => $postId,
    			'user_id' => Auth::user()->id,
    			'comment' => $request->comment
    		]);

    		return response()->json([
    			'message' => 'Commented Successfully!'
    		]); 
    	}

    	abort(403, 'Unauthorized access');
    }

    public function commentReply(Request $request, $postId, $commentId){
        if(Auth::user()->tokenCan('post:comment')){
            $request->validate([
                'comment' => 'required|string|max:8000'
            ]);

            $post = Post::findOrFail($postId);
            $comment = Comment::findOrFail($commentId);

            $reply = Comment::create([
                'post_id' => $postId,
                'user_id' => Auth::user()->id,
                'parent_id' => $comment->id,
                'comment' => $request->comment
            ]);

            return response()->json([
                'message' => 'Replied Successfully!'
            ]); 
        }

        abort(403, 'Unauthorized access');
    }

    public function getPostsByUser($userId){
        $posts = Post::where('user_id', $userId)->get();
        return PostResource::collection($posts);
    }

    public function getNewsfeedPosts(){
        $userId = Auth::user()->id;
        $friends = Auth::user()->users()->get();
        $friendsTwo = Auth::user()->friendsPivotUserTwo()->get();
        $merge = $friends->merge($friendsTwo);
        foreach($merge as $mer){
            dump($mer->first_name);
        }
        exit;
        $posts = Post::with('friends')->toSql();
        dd($posts);
        return PostResource::collection($posts);
    }
}
