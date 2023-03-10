<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use App\Like;
use App\Http\Requests\PostRequest;
use App\Http\Requests\PostImageRequest;
use App\Services\FileUploadService;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $user = \Auth::user();
        $follow_user_ids = $user->follow_users->pluck('id');
        $user_posts = $user->posts()->orWhereIn('user_id', $follow_user_ids )->latest()->paginate(5);
        return view('posts.index', [
            'title' => '投稿一覧',
            'posts' => $user_posts,
            'recommended_users' => User::recommend($user->id)->get()
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
    private function saveImage($image){
        $path = '';
        if( isset($image) === true ){
            $path = $image->store('photos', 'public');
        }
        return $path; //存在しない場合は空文字
    }
    
    public function store(PostRequest $request, FileUploadService $service)
    {
        //画像投稿処理
        $path = $service->saveImage($request->file('image'));
        
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
    public function edit(Post $post)
    {
        // $post = Post::find($post); //不要になる！
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
    public function updateImage($id, PostImageRequest $request, FileUploadService $service){
        
        //画像投稿処理
        $path = $service->saveImage($request->file('image'));
        
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
    //いいねの削除と追加
    public function toggleLike($id){
        $user = \Auth::user();
        $post = Post::find($id);
        
        if($post->isLikedBy($user)){
            //いいねの取り消し
            $post->likes->where('user_id', $user->id)->first()->delete();
            \Session::flash('success', 'いいねを取り消しました');
        } else {
            //いいねを設定
            Like::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
            \Session::flash('success', 'いいねしました');
        }
        return redirect('/posts');
    }
}