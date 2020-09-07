<?php
use think\facade\Route;

Route::post('me', 'Auth/me');
Route::post('sign', 'Auth/sign');
Route::post('login', 'Auth/login');
Route::post('logout', 'Auth/logout');

// 用户路由
Route::resource('user', 'User');
Route::post('user/follow', 'User/follow');
Route::post('user/unfollow', 'User/unfollow');
// 推文路由
Route::resource('tweet', 'Tweet');