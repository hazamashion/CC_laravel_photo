@extends('layouts.logged_in')
 
@section('title', $title)
 
@section('content')
    <h1>{{ $title }}</h1>
    <h2>おすすめユーザー</h2>
    <ul class="recommended_users">
        @forelse($recommended_users as $recommended_user)
            <li>
                <a href="{{ route('users.show', $recommended_user) }}">{{ $recommended_user->name }}</a>
                @if(Auth::user()->isFollowing($recommended_user))
                    <form method="post" action="{{ route('follows.destroy', $recommended_user) }}" class="follow">
                        @csrf
                        @method('delete')
                        <input type="submit" value="フォロー解除">
                    </form>
                @else
                    <form method="post" action="{{ route('follows.store') }}" class="follow">
                        @csrf
                        <input type="hidden" name="follow_id" value="{{ $recommended_user->id }}">
                        <input type="submit" value="フォロー">
                    </form>
                @endif
            </li>
        @empty
            <li>おすすめユーザーはいません。</li>
        @endforelse
    </ul>
    <a href="{{route('posts.create')}}">新規投稿</a>
    <ul class="posts">
        @forelse($posts as $post)
            <li class="post">
                <div class="post_content">
                    <div class="post_body">
                        <div class="post_body_heading">
                            投稿者:{{ $post->user->name }}
                            ({{ $post->created_at }})
                        </div>
                        <div class="post_body_main">
                            <div class="post_body_main_img">
                                @if($post->image !== '')
                                    <img src="{{ asset('storage/' . $post->image) }}">
                                @else
                                    <img src="{{ asset('images/no_image.png') }}">
                                @endif
                                <a href="{{ route('posts.edit_image', $post) }}">画像を変更</a>
                            </div>
                            <div class="post_body_main_content">
                                {{ $post->comment }} 
                            </div>
                        </div>
                        <div class="post_body_footer">
                            <a class="like_button">{{ $post->isLikedBy(Auth::user()) ? '★' : '☆' }}</a>
                            <form method="post" class="like" action="{{ route('posts.toggle_like', $post) }}">
                                @csrf
                                @method('patch')
                            </form>
                            [<a href="{{ route('posts.edit', $post) }}">編集</a>]
                            <form method="post" class="delete" action="{{ route('posts.destroy', $post) }}">
                                @csrf
                                @method('delete')
                                <input type="submit" value="削除">
                            </form>
                        </div>
                    </div>
                    <div class="post_comments">
                        <span class="post_comments_header">コメント</span>
                        <ul class="post_comments_body">
                            @forelse($post->comments as $comment)
                                <li>{{ $comment->user->name }}: {{ $comment->body }}</li>
                            @empty
                                <li>コメントはありません。</li>
                            @endforelse                        
                        </ul>
                    </div>
                    <form method="post" action="{{ route('comments.store') }}">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <label>
                            コメントを追加：
                            <input type="text" name="body">
                        </label>
                        <input type="submit" value="送信">
                    </form>
                </div>
            </li>
        @empty
            <li>書き込みはありません。</li>
        @endforelse
  </ul>
  {{ $posts->links() }}
  <script>
    /* global $ */
    $('.like_button').each(function(){
        $(this).on('click', function(){
            $(this).next().submit();
        });
    });
  </script>
@endsection