<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestfulController;

class ShopController extends RestfulController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = 'App\Models\Shop';
        $this->resource = 'App\Resources\Shop';
        $this->modulekey = 'shop';
        $this->moduleName = 'shops';
    }
}
