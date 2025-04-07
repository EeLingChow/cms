<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;

class ModuleController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->modulekey = 'module';
        $this->moduleName = 'modules';
        $this->viewFolder = 'admins.modules';
        $this->apiController = 'App\Http\Controllers\Api\ModuleController';
    }
}
