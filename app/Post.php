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
        // 最新10件取得
        return $query->where('user_id', '=', $self_id)->latest()->limit(10);
    }
}