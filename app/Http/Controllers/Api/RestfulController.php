<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\RequestParser;
use App\Helpers\QueryParser;
use App\Helpers\RestHelper;

use Illuminate\Pagination\Paginator;

class RestfulController extends Controller
{
    use RequestParser, QueryParser, RestHelper;

    protected $modulekey;
    protected $moduleName;
    protected $model;
    protected $resource;

    public function __construct() {}

    public function index(Request $request)
    {
        $model = new $this->model;

        $data = $this->parseQueryRequest($request);
        extract($data);

        $query = $this->parseQuery($model, $model->getQuery(), $filters, $orders);
        $model->apply($request->user(), $query);

        Paginator::currentPageResolver(function () use ($pagination) {
            return $pagination['page'];
        });

        $collections = $this->resource::collection($query->paginate($pagination['perpage']));

        return $this->response(200, $collections);
    }

    public function store(Request $request)
    {
        $postdata = $request->all();
        $data = [];
        foreach ($postdata as $k => $v) {
            if (is_array($v) && !empty($v)) {
                $data[$k] = $v;
            } else if (strlen($v) > 0) {
                $data[$k] = $v;
            }
        }
        // $data = array_filter($request->all(), 'strlen');

        $model = new $this->model;

        if (!$model->validate($data)) {
            $errors = $this->getErrors($model->errors);
            return $this->error(422, 'There are one or more field having errors.', $errors);
        }

        $model->fillFromRequest($request, $data);

        if ($model->save()) {
            $model->afterSave($request);
            $data = new $this->resource($model);

            return $this->response(201, $data, 'Record is successfully created.');
        }

        return $this->error(500, 'Internal Error');
    }

    public function show($id, Request $request, $jsonResponse = true)
    {
        $model = new $this->model;

        $query = $this->model::query()->where('id', $id);
        $model->apply($request->user(), $query);

        $result = $query->first();

        if (!$result) {
            return $this->error(404, 'Record Not Found');
        }

        if ($jsonResponse) {
            $data = new $this->resource($result);
            return $this->response(200, $data);
        }

        return $result;
    }

    public function update($id, Request $request)
    {
        $postdata = $request->all();
        $data = [];
        foreach ($postdata as $k => $v) {
            if (is_array($v) && !empty($v)) {
                $data[$k] = $v;
            } else if (strlen($v) > 0) {
                $data[$k] = $v;
            }
        }

        $model = new $this->model;
        if (!$model->validate($data, $id, true)) {
            $errors = $this->getErrors($model->errors);
            return $this->error(422, 'There are one or more field having errors.', $errors);
        }

        $query = $this->model::query()->where('id', $id);
        $model->applySave($request->user(), $query);
        $item = $query->first();

        if (!$item) {
            return $this->error(404, 'Record Not Found');
        }

        $item->isUpdate();
        $item->fillFromRequest($request, $data);

        if ($item->save()) {
            $item->afterSave($request);

            $data = new $this->resource($item);
            return $this->response(200, $data, 'Record is successfully updated.');
        }

        return $this->error(500, 'Internal Error');
    }

    public function delete($id, Request $request)
    {
        $model = new $this->model;

        $query = $this->model::query()->where('id', $id);
        $model->apply($request->user(), $query);
        $item = $query->first();

        if (!$item) {
            return $this->error(404, 'Record Not Found');
        }

        if ($item->delete()) {
            return $this->noContent();
        }

        return $this->error(500, 'Internal Error');
    }
}
