@extends('layouts.logged_in')
 
@section('title', $title)
 
@section('content')
    <h1>{{ $title }}</h1>
    
    <ul class="mutual_follow_users">
        @forelse($mutual_follow_users as $mutual_follow_user)
            <li class="mutual_follow_user">
                @if($mutual_follow_user->image !== '')
                    <img src="{{ asset('storage/user_photos/' . $mutual_follow_user->image) }}">
                @else
                    <img src="{{ asset('images/no_image.png') }}">
                @endif
                {{ $mutual_follow_user->name }}
                @if(Auth::user()->isFollowing($mutual_follow_user))
                    <form method="post" action="{{route('follows.destroy', $mutual_follow_user)}}" class="follow">
                        @csrf
                        @method('delete')
                        <input type="submit" value="フォロー解除">
                    </form>
                @else
                    <form method="post" action="{{route('follows.store')}}" class="follow">
                        @csrf
                        <input type="hidden" name="follow_id" value="{{ $mutual_follow_user->id }}">
                        <input type="submit" value="フォロー">
                    </form>
                @endif
            </li>
        @empty
            <li>相互フォローのユーザーはいません。</li>
        @endforelse
    </ul>
@endsection