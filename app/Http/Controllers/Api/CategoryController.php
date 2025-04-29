<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestfulController;
use App\Resources\Category;

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

    public function getAllCategories()
    {
        $categories = $this->model::orderBy('name', 'asc')->get();

        $collections = Category::collection($categories);

        return $this->response(200, $collections);
    }
}
