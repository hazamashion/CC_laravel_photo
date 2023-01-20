@extends('layouts.logged_in')
 
@section('title', $title)
 
@section('content')
  <h1>{{ $title }}</h1>
  <form method="post" action="{{ route('posts.update', $post) }}">
      @csrf
      @method('patch')
      <div>
          <label>
              コメント:
              <input type="text" name="comment" value="{{ $post->comment }}">
          </label>
      </div>
      
     <input type="submit" value="投稿">
  </form>
@endsection