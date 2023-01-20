<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Comment;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request){
        Comment::create([
            'post_id' => $request->post_id,
            'user_id' => \Auth::user()->id,
            'body' => $request->body,
        ]);
        
        session()->flash('success', 'コメントを投稿しました');
        return redirect('/posts');
    }
}
