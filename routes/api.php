<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/shops/all', ['uses' => "Api\ShopController@getAllShops"])
    ->name("api.shops.all");

Route::get('/shops/show', ['uses' => "Api\ShopController@getShopDetail"])
    ->name("api.shops.detail");

Route::post('/shops/search-by-category', ['uses' => "Api\ShopController@searchByCategory"])
    ->name("api.shops.search-by-category");

Route::get('/categories/all', ['uses' => "Api\CategoryController@getAllCategories"])
    ->name("api.categories.all");

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/change-password', ['uses' => "Api\AdminController@changePassword"])
        ->name('api.admins.change-password')
        ->middleware("audit:admin,change-password");

    add_api_module_routes('admin', [
        'prefix' => 'admins',
        'name' => 'admins',
    ], function () {
        Route::post('/api/customize-permission/{id}', ['uses' => "Api\AdminController@apiCustomizePermission"])->name("admins.api.customize-permission")
            ->where('id', '\d+')
            ->middleware("audit:admin,customize-permission");
    });

    add_api_module_routes('floor', [
        'prefix' => 'floors',
        'name' => 'floors',
    ]);

    add_api_module_routes('category', [
        'prefix' => 'categories',
        'name' => 'categories',
    ]);

    add_api_module_routes('shop', [
        'prefix' => 'shops',
        'name' => 'shops',
    ]);

    add_api_module_routes('module', [
        'prefix' => 'modules',
        'name' => 'modules',
    ]);

    add_api_module_routes('profile', [
        'prefix' => 'profiles',
        'name' => 'profiles',
    ]);

    add_api_module_routes('auditLog', [
        'prefix' => 'audit-logs',
        'name' => 'audit-logs',
    ]);
});
