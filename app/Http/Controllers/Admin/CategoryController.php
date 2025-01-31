<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;

class CategoryController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->modulekey = 'category';
        $this->moduleName = 'categories';
        $this->viewFolder = 'admins.categories';
        $this->apiController = 'App\Http\Controllers\Api\CategoryController';
    }
}
