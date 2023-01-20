@extends('layouts.logged_in')
 
@section('title', $title)
 
@section('content')
  <h1>{{ $title }}</h1>
  <form method="post" action="{{ route('posts.store') }}" enctype="multipart/form-data">
      @csrf
      <div>
        <lavel>
          コメント:
          <input type="text" name="comment">
        </lavel>
      </div>
      <div>
        <lavel>
          画像：
          <input type="file" name="image">
        </lavel>
      </div>

      <input type="submit" value="投稿">
  </form>
@endsection