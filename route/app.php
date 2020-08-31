<?php
use think\facade\Route;

Route::post('me', 'Auth/me');
Route::post('sign', 'Auth/sign');
Route::post('login', 'Auth/login');
Route::post('logout', 'Auth/logout');

Route::resource('user', 'User');