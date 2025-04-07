<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;

use App\Models\Module;
use App\Models\Profile;

class ProfileController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->modulekey = 'profile';
        $this->moduleName = 'profiles';
        $this->viewFolder = 'admins.profiles';
        $this->apiController = 'App\Http\Controllers\Api\ProfileController';
    }

    public function create(Request $request)
    {
        $actionRoute = route("api.{$this->moduleName}.store");

        $module = new Module;
        $module->setAppUser($request->user());
        $modules = $module->getModuleList();

        return view("{$this->viewFolder}.create", compact('actionRoute', 'modules'));
    }

    public function edit($id, Request $request)
    {
        $model = new Profile;
        $query = Profile::query()->where('id', $id);
        $model->apply($request->user(), $query);

        $data = $query->first();

        if (!$data) {
            return $this->error(404, 'Record Not Found');
        }

        $module = new Module;
        $module->setAppUser($request->user());
        $modules = $module->getModuleList();

        $actionRoute = route("api.{$this->moduleName}.update", ['id' => $id]);

        $profileModules = [];
        foreach ($data->modules as $m) {
            $binary = str_pad(decbin($m->pivot->permission) . '', 4, '0', STR_PAD_LEFT);
            $profileModules[$m->id] = $binary;
        }

        return view("{$this->viewFolder}.edit", compact('actionRoute', 'data', 'modules', 'profileModules'));
    }
}
