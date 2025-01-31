<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;

class FloorController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->modulekey = 'floor';
        $this->moduleName = 'floors';
        $this->viewFolder = 'admins.floors';
        $this->apiController = 'App\Http\Controllers\Api\FloorController';
    }
}
