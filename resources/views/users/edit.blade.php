@extends('layouts.logged_in')

@section('content')
    <h1>{{ $title }}</h1>
    <form method="post" action="{{ route('users.update') }}" >
        @csrf
        @method('patch')
        <input type="hidden" name="id" value="{{ $user->id }}">
        [<a href="{{ route('users.show', $user->id) }}">戻る</a>]
        <div>
            <label>
                名前:<input type="text" name="name">
            </label>
        </div>
        <div>
            <label>
                メールアドレス:<input type="text" name="email">
            </label>
        </div>
        <div>
            <label>
                <p>プロフィール:</p>
                <textarea name="profile" cols="40" rows="10"></textarea>
            </label>
        </div>
        <input type="submit" value="更新">
    </form>
@endsection