<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LikeController extends Controller
{
    // いいね一覧
    public function index()
    {
        return view('likes.index', [
          'title' => 'いいね一覧',
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
