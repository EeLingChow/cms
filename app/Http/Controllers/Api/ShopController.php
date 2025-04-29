<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestfulController;
use App\Resources\Shop;
use App\Models\Category;

use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Validator;

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

    public function getAllShops()
    {
        $shops = $this->model::with(['floor', 'categories'])
            ->orderBy('name', 'asc')
            ->get();


        $collections = Shop::collection($shops);

        return $this->response(200, $collections);
    }

    public function getShopDetail(Request $request)
    {
        $data = array_filter($request->all());

        $validation = Validator::make($data, [
            'id' => 'numeric',
        ]);

        if ($validation->fails()) {
            return $this->error(422, 'Invalid Parameters', $validation->errors()->getMessages());
        }

        $shop = $this->model::find($data['id']);
        $result = new Shop($shop);

        return $this->response(200, $result);
    }

    public function searchByCategory(Request $request)
    {
        $data = array_filter($request->all());

        $validation = Validator::make($data, [
            'category_id' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            return $this->error(422, 'Invalid Parameters', $validation->errors()->getMessages());
        }

        $category_id = $data['category_id'];

        $category = Category::find($category_id);

        if (!$category) {
            return $this->error(404, 'Invalid Category');
        }

        $shops = $this->model::whereHas('categories', function ($query) use ($category_id) {
            $query->where('category.id', $category_id);
        })->get();

        $collections = Shop::collection($shops);

        return $this->response(200, $collections);
    }
}
