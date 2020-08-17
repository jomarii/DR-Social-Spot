<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['user_id', 'parent_id', 'post'];

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function comments(){
    	return $this->hasMany(Comment::class)
                    ->whereNull('parent_id')
                    ->get();
    }

    public function likes(){
    	return $this->belongsToMany(User::class, 'like_posts');
    }

    public function likesCount(){
        return $this->likes()->count();
    }

    public function likers(){
        return $this->likes()->withPivot('post_id')->get();
    }

    public function sharedFrom(){
        return $this->belongsTo(Post::class, 'parent_id');
    }
}
