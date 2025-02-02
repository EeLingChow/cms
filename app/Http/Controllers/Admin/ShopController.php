<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;

class ShopController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->modulekey = 'shop';
        $this->moduleName = 'shops';
        $this->viewFolder = 'admins.shops';
        $this->apiController = 'App\Http\Controllers\Api\ShopController';
    }
}
