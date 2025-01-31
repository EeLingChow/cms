<?php

use Illuminate\Support\Facades\Route;

$baseFolder = "Admin";

Route::any('/login', ['uses' => "{$baseFolder}\AdminController@login"])
    ->name('admins.login');

Route::group(['middleware' => 'auth:admin'], function () use ($baseFolder) {
    Route::get('/', ['uses' => "{$baseFolder}\AdminController@home"])
        ->name('admins.home');

    Route::any('/change-password', ['uses' => "{$baseFolder}\AdminController@changePassword"])
        ->name('admins.change-password');

    Route::get('/logout', ['uses' => "{$baseFolder}\AdminController@logout"])
        ->name('admins.logout');

    add_module_routes('admin', [
        'prefix' => 'admins',
        'name' => 'admins',
    ]);

    add_module_routes('floor', [
        'prefix' => 'floors',
        'name' => 'floors',
    ]);

    add_module_routes('category', [
        'prefix' => 'categories',
        'name' => 'categories',
    ]);
});
