<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['user_id', 'comment', 'image'];
    
    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public function comments(){
        return $this->hasMany('App\Comment');
    }
    
    public function scopeRecommend($query, $self_id){
        // ランダムに３つの投稿を取得
        return $query->where('user_id', '=', $self_id)->inRandomOrder()->limit(3);
    }
}