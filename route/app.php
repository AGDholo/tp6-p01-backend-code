<?php
use think\facade\Route;

Route::post('sign', 'Auth/sign');
Route::post('login', 'Auth/login');
Route::post('logout', 'Auth/logout');