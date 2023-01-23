@extends('layouts.logged_in')
 
@section('title', $title)
 
@section('content')
    <h1>{{ $title }}</h1>
  
    <ul class="follow_users">
        @forelse($follow_users as $follow_user)
            <li class="follow_user">
                @if($follow_user->image !== '')
                    <img src="{{ asset('storage/user_photos/' . $follow_user->image) }}">
                @else
                    <img src="{{ asset('images/no_image.png') }}">
                @endif
                <br>
                {{ $follow_user->name }}
                <form method="post" action="{{ route('follows.destroy', $follow_user) }}" class="follow">
                    @csrf
                    @method('delete')
                    <input type="submit" value="フォロー解除">
                </form>
            </li>
        @empty
            <li>フォローしているユーザーはいません。</li>
        @endforelse
    </ul>
@endsection