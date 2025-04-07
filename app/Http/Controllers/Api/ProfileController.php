<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestfulController;

class ProfileController extends RestfulController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = 'App\Models\Profile';
        $this->resource = 'App\Resources\Profile';
        $this->modulekey = 'profile';
        $this->moduleName = 'profiles';
    }
}
