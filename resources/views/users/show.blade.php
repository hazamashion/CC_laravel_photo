@extends('layouts.logged_in')

@section('content')
    <h1>{{ $title }}</h1>
    <div>
        <!--ログインユーザーとプロフィールユーザーの照合-->
        @if( \Auth::user()->id === $user->id)
            [<a href="{{ route('users.edit') }}">編集</a>]
        @endif
        <div>
            <h2>名前</h2>
            <p>{{ $user->name }}</p>
        </div>
        <div>
            <h2>プロフィール画像</h2>
            @if($user->image !== '')
                <img src="{{ asset('storage/' . $user->image) }}">
            @else
                <img src="{{ asset('images/no_image.png') }}">
            @endif
            <!--ログインユーザーとプロフィールユーザーの照合-->
            @if( \Auth::user()->id === $user->id)
                <div>
                    <a href="{{ route('users.edit_image', $user) }}">画像を変更</a>
                </div>
            @endif
        </div>
        <div>
            <h2>プロフィール</h2>
            @if($user->profile !== '')
                <p>{{ $user->profile }}</p>
            @else
                <p>プロフィールが設定されていません。</p>
            @endif
        </div>
        <div>
            <h2>{{ $user->name }}のおすすめ投稿</h2>    
            <ul class="posts">
                @forelse($recommend_posts as $recommend_post)
                    <li class="post">
                        <div class="post_content">
                            <div class="post_body">
                                <div class="post_body_heading">
                                    投稿者:{{ $recommend_post->user->name }}
                                    ({{ $recommend_post->created_at }})
                                </div>
                                <div class="post_body_main">
                                    <div class="post_body_main_img">
                                        @if($recommend_post->image !== '')
                                            <img src="{{ asset('storage/' . $recommend_post->image) }}">
                                        @else
                                            <img src="{{ asset('images/no_image.png') }}">
                                        @endif
                                        <!--ログインユーザーとプロフィールユーザーの照合-->
                                        @if( \Auth::user()->id === $user->id)
                                            <div>
                                                <a href="{{ route('posts.edit_image', $recommend_post) }}">画像を変更</a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="post_body_main_content">
                                        {{ $recommend_post->comment }}
                                    </div>
                                </div>
                                <!--ログインユーザーとプロフィールユーザーの照合-->
                                @if( \Auth::user()->id === $user->id)
                                    <div class="post_body_footer">
                                        [<a href="{{ route('posts.edit', $recommend_post) }}">編集</a>]
                                        <form method="post" class="delete" action="{{ route('posts.destroy', $recommend_post) }}">
                                            @csrf
                                            @method('delete')
                                            <input type="submit" value="削除">
                                        </form>
                                    </div>
                                @endif                                
                            </div>
                        </div>
                        <div class="post_comments">
                            <span class="post_comments_header">コメント</span>
                            <ul class="post_comments_body">
                                @forelse($recommend_post->comments() as $comment)
                                    <li>{{ $comment->user->name }}: {{ $comment->body }}</li>
                                @empty
                                    <li>コメントはありません。</li>
                                @endforelse
                            </ul>
                        </div>
                        <form method="post" action="{{ route('comments.store') }}">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $recommend_post->id }}">
                            <label>
                                コメントを追加:
                                <input type="text" name="body">
                            </label>
                            <input type="submit" value="送信">
                        </form>
                    </li>
                @empty
                    <li>書き込みはありません。</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection