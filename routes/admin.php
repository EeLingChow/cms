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
    ], function () {
        Route::get('/customize-permission/{id}', ['uses' => "Admin\AdminController@customizePermission"])->name("admins.customize-permission")
            ->where('id', '\d+');
    });

    add_module_routes('floor', [
        'prefix' => 'floors',
        'name' => 'floors',
    ]);

    add_module_routes('category', [
        'prefix' => 'categories',
        'name' => 'categories',
    ]);

    add_module_routes('shop', [
        'prefix' => 'shops',
        'name' => 'shops',
    ]);

    add_module_routes('module', [
        'prefix' => 'modules',
        'name' => 'modules',
    ]);

    add_module_routes('profile', [
        'prefix' => 'profiles',
        'name' => 'profiles',
    ]);

    add_module_routes('auditLog', [
        'prefix' => 'audit-logs',
        'name' => 'audit-logs',
    ]);
});
