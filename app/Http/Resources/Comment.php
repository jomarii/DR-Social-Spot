<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CommentReply as CommentReplyResource;

class Comment extends JsonResource
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
            'comment_id' => $this->id,
            'post_id' => $this->post_id,
            'user_id' => $this->user_id,
            'user' => $this->user->getFullNameAttribute(),
            'comment' => $this->comment,
            'created_at' => $this->created_at->format('M d, y h:ia'),
            'replies' => CommentReplyResource::collection($this->replies())
        ];
    }
}
