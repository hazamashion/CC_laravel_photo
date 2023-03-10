<?php

namespace App\Http\Controllers;

use App\User;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ProfileImageRequest;
use App\Services\FileUploadService;


class UserController extends Controller
{
    public function edit(){
        $user = User::find(\Auth::user()->id);
        
        return view('users.edit', [
            'title' => 'プロフィール編集',
            'user' => $user,
        ]);
    }
    
    public function update(ProfileIRequest $request){
        $user = User::find(\Auth::user()->id);
        $user->update($request->only(['name', 'email', 'profile']));
        
        session()->flash('success', 'プロフィールを編集しました。');
        return redirect()->route('users.show', $user);
    }
    
    public function editImage(){
        $user = User::find(\Auth::user()->id);
        
        return view('users.edit_image', [
            'title' => '画像変更',
            'user' => $user,
        ]);
    }
    
    public function updateImage(ProfileImageRequest $request, FileUploadService $service){
        
        //画像投稿処理
        $path = $service->saveImage($request->file('image'));
        
        $user = User::find(\Auth::user()->id);
        
        //変更前の画像の削除
        if( $user->image !== ''){
            \Storage::disk('public')->delete(\Storage::url($user->image));
        }
        
        $user->update([
            'image' => $path,
        ]);
        
        session()->flash('success', '画像を変更しました。');
        return redirect()->route('users.show', $user);
    }
    // 投稿詳細
    public function show($id)
    {
        $user = User::find($id);
        $collection = Post::recommend($user->id)->get();
        $recommend_posts = $collection->random(2);
        
        return view('users.show', [
          'title' => 'プロフィール',
          'user' => $user,
          'recommend_posts' => $recommend_posts,
        ]);
    }
}
