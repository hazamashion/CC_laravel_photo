<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use App\Http\Requests\PostRequest;
use App\Http\Requests\PostImageRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        return view('posts.index', [
            'title' => '投稿一覧',
            'posts' => $user->posts()->latest()->get(),
            'recommend_users' => User::recommend($user->id)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create', [
            'title' => '新規投稿',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        //画像投稿処理
        $path = '';
        $image = $request->file('image');
        if( isset($image) === true ){
            //publicディスク（storage/app/public/)のphotosディレクトリに保存
            $path = $image->store('photos', 'public');
        }
        
        Post::create([
            'user_id' => \Auth::user()->id,
            'comment' => $request->comment,
            'image' => $path,//ファイルパスを保存
        ]);
        
        session()->flash('success', '投稿を追加しました。');
        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('posts.show', [
          'title' => '投稿詳細',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        return view('posts.edit', [
          'title' => '投稿編集',
          'post' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, PostRequest $request)
    {
        $post = Post::find($id);
        $post->update($request->only(['comment']));
        session()->flash('success', '投稿を編集しました');
        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        
        $post = Post::find($id);
        
        //現在の画像
        if($post->image !== ''){
            \Storage::disk('public')->delete($post->image);
        }
        
        $post->delete();
        session()->flash('success', '投稿を削除しました');
        return redirect()->route('posts.index');
    }
    
    public function __construct(){
        
        $this->middleware('auth');
        
    }
    
    public function editImage($id){
        $post = Post::find($id);
        return view('posts.edit_image', [
            'title' => '画像変更画面',
            'post' => $post,
        ]);   
    }
    //画像変更処理
    public function updateImage($id, PostImageRequest $request){
        
        //画像投稿処理
        $path = '';
        $image = $request->file('image');
        
        if( isset($image) ===  true ){
            //publicディスク(storage/app/public/)のphotosディレクトリに保存
            $path = $image->store('photos', 'public');
        }
        
        $post = Post::find($id);
        
        //変更前の画像の削除
        if($post->image !== ''){
            \Storage::disk('public')->delete(\Storage::url($post->image));
        }
        
        $post->update([
            'image' => $path,//ファイルを保存
        ]);
        
        session()->flash('success', '画像を変更しました');
        return redirect()->route('posts.index');
    }
}