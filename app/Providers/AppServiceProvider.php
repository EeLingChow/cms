<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Validators\CustomCheckValidator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Parser\Cookies;
use Tymon\JWTAuth\Http\Parser\AuthHeaders;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app('validator')->resolver(function ($translator, $data, $rules, $messages = array(), $customAttributes = array()) {
            return new CustomCheckValidator($translator, $data, $rules, $messages, $customAttributes);
        });
    }
}
