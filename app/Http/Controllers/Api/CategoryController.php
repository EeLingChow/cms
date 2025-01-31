<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestfulController;

class CategoryController extends RestfulController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = 'App\Models\Category';
        $this->resource = 'App\Resources\Category';
        $this->modulekey = 'category';
        $this->moduleName = 'categories';
    }
}
