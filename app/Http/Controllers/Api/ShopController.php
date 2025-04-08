<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestfulController;
use App\Resources\GetAllShops;
use App\Resources\SearchShopsByCategory;
use App\Models\Category;

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

    public function getAllShops()
    {
        $shops = $this->model::with(['floor', 'categories'])
            ->orderBy('name', 'asc')
            ->get();

        $collections = GetAllShops::collection($shops);

        return $this->response(200, $collections);
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

        $collections = GetAllShops::collection($shops);

        return $this->response(200, $collections);
    }
}
