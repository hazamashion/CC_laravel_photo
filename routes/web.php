<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('posts', 'PostController');

Route::resource('likes', 'LikeController')->only([
    'index', 'store', 'destroy'
]);

Route::resource('follows', 'FollowController')->only([
    'index', 'store', 'destroy'
]);

Route::get('/follower', 'FollowController@followerIndex');

Auth::routes();

Route::resource('comments', 'CommentController')->only([
    'store', 'destroy'
]);

Route::get('/posts/{post}/edit_image', 'PostController@editImage')->name('posts.edit_image');
Route::patch('/posts/{post}/edit_image', 'PostController@updateImage')->name('posts.update_image');

Route::get('/users/edit', 'UserController@edit')->name('users.edit');
Route::patch('/users', 'UserController@update')->name('users.update');
Route::get('/users/edit_image', 'UserController@editImage')->name('users.edit_image');
Route::patch('/users/edit_image', 'UserController@updateImage')->name('users.update_image');

Route::resource('users', 'UserController')->only([
    'show',
]);