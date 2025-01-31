<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestfulController;

class FloorController extends RestfulController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = 'App\Models\Floor';
        $this->resource = 'App\Resources\Floor';
        $this->modulekey = 'floor';
        $this->moduleName = 'floors';
    }
}
