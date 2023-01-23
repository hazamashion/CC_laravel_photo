<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LikeController extends Controller
{
    // いいね一覧
    public function index()
    {
        $like_posts = \Auth::user()->likePosts;
        return view('likes.index', [
          'title' => 'いいね一覧',
          'like_posts' => $like_posts,
        ]);
    }
 
    // いいね追加処理
    public function store(Request $request)
    {
        //
    }
 
    // いいね削除処理
    public function destroy($id)
    {
        //
    }
    
    public function __construct(){
        
        $this->middleware('auth');
    }
}
