<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentReply extends JsonResource
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
            'parent_id' => $this->parent_id,
            'comment_id' => $this->id,
            'user_id' => $this->user_id,
            'user' => $this->user->getFullNameAttribute(),
            'comment' => $this->comment,
            'created_at' => $this->created_at->format('M d, Y h:ia'),
        ];
    }
}
