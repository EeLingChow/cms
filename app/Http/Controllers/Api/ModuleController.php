<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestfulController;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class ModuleController extends RestfulController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = 'App\Models\Module';
        $this->resource = 'App\Resources\Module';
        $this->modulekey = 'module';
        $this->moduleName = 'modules';
    }

    public function index(Request $request)
    {
        $model = new $this->model;

        $data = $this->parseQueryRequest($request);
        extract($data);

        $query = $this->parseQuery($model, $model->getQuery(), $filters, $orders);
        $query->where('is_master', '0');
        $model->apply($request->user(), $query);

        Paginator::currentPageResolver(function () use ($pagination) {
            return $pagination['page'];
        });

        $collections = $this->resource::collection($query->paginate($pagination['perpage']));

        return $this->response(200, $collections);
    }
}
