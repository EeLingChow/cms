<?php

use Illuminate\Support\Facades\Route;

$baseFolder = "Admin";

Route::any('/login', ['uses' => "{$baseFolder}\AdminController@login"])
    ->name('admins.login');
