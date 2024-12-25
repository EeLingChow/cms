<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $modulekey;
    protected $moduleName;
    protected $viewFolder;
    protected $apiController;

    public function __construct() {}


    /**
     * get user listing
     *
     *
     * @GET("/")
     * @Versions({"v1"})
     * @Request({"filters": {}, "include": [], "limit": 10, "order": "created_at desc"})
     */
    public function index(Request $request)
    {
        return view("{$this->viewFolder}.list", [
            'modulekey' => $this->modulekey,
            'modulename' => $this->moduleName,
        ]);
    }

    public function create(Request $request)
    {
        $actionRoute = route("api.{$this->moduleName}.store");
        $actionMethod = 'post';
        return view("{$this->viewFolder}.create", compact('actionRoute', 'actionMethod'));
    }

    public function edit($id, Request $request)
    {
        $actionRoute = route("api.{$this->moduleName}.update", ['id' => $id]);
        $actionMethod = 'put';
        $data = app($this->apiController)->show($id, $request, false);

        if (!$data) {
            abort(404);
        }

        // $data = json_decode($data->toJson());

        return view("{$this->viewFolder}.edit", compact('actionRoute', 'actionMethod', 'data'));
    }
}
