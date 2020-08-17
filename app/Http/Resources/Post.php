<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Comment as CommentResource;
use App\Http\Resources\Profile as ProfileResource;
use App\Http\Resources\SharedPost as SharedPostResource;

class Post extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'post_id' => $this->id,
            'user_id' => $this->user_id,
            'user' => $this->user->getFullNameAttribute(),
            'post' => $this->post,
            'sharedFrom' => new SharedPostResource($this->sharedFrom),
            'likes' => $this->likesCount(),
            'likers' => ProfileResource::collection($this->likers()),
            'created_at' => $this->created_at->format('M d, Y h:ia'),
            'comments' => CommentResource::collection($this->comments())
        ];
    }
}
