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

/* Public */

Route::get('/shops/all', ['uses' => "Api\ShopController@getAllShops"])
    ->name("api.shops.all");

Route::get('/shops/show', ['uses' => "Api\ShopController@getShopDetail"])
    ->name("api.shops.detail");

Route::post('/shops/search-by-category', ['uses' => "Api\ShopController@searchByCategory"])
    ->name("api.shops.search-by-category");

Route::get('/categories/all', ['uses' => "Api\CategoryController@getAllCategories"])
    ->name("api.categories.all");

Route::post('/register', ['uses' => "Api\AuthController@register"]);
Route::post('/login', ['uses' => "Api\AuthController@login"]);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', ['uses' => "Api\AuthController@me"]);
    Route::post('/logout', ['uses' => "Api\AuthController@logout"]);
    Route::get('/bookmarks', ['uses' => "Api\BookmarkController@index"]);
    Route::post('/bookmarks/{shop}', ['uses' => "Api\BookmarkController@store"]);
    Route::delete('/bookmarks/{shop}', ['uses' => "Api\BookmarkController@destroy"]);
});
