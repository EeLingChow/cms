<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestfulController;
use Illuminate\Http\Request;

use Validator;

class AdminController extends RestfulController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = 'App\Models\Admin';
        $this->resource = 'App\Resources\Admin';
        $this->modulekey = 'admin';
        $this->moduleName = 'admins';
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return $this->error(401, 'Permission Denied');
        }

        $v = Validator::make($request->all(), [
            'current_password' => 'required|password_match:' . $user->password,
            'new_password' => 'required|string|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($v->fails()) {
            return $this->error(422, 'Invalid Parameters', $v->errors()->getMessages());
        }

        $user->password = $request->get('new_password');

        if ($user->save()) {
            return $this->responseWithMessage(200, 'Password successfully updated.');
        }

        return $this->error('500', 'Internal Error');
    }

    public function apiCustomizePermission($id, Request $request)
    {
        $parsed = $compare = $profileModules = [];
        $postdata = $request->all();

        $model = new $this->model;
        $query = $this->model::query()->where('id', $id);
        $model->apply($request->user(), $query);

        $data = $query->first();

        if (!$data) {
            return $this->error(404, 'Record Not Found');
        }

        foreach ($data->profile->modules as $m) {
            $binary = str_pad(decbin($m->pivot->permission) . '', 4, '0', STR_PAD_LEFT);
            $profileModules[$m->id] = [
                'module_id' => $m->id,
                'permission' => $m->pivot->permission,
            ];
        }

        if (isset($postdata['modules']) && !empty($postdata['modules'])) {
            foreach ($postdata['modules'] as $mid) {
                $compare[$mid] = [
                    'module_id' => $mid,
                    'permission' => isset($postdata['permissions'][$mid]) ? array_sum($postdata['permissions'][$mid]) : 0,
                ];
            }
        }

        DB::table('module_assignment')
            ->where('admin_id', $id)
            ->delete();

        foreach ($profileModules as $mid => $d) {
            if (!isset($compare[$mid])) {
                $parsed[] = [
                    'admin_id' => $id,
                    'module_id' => $mid,
                    'plusminus' => -1,
                    'permission' => 0,
                ];
            } else if (!empty(array_diff($d, $compare[$mid]))) {
                $parsed[] = [
                    'admin_id' => $id,
                    'module_id' => $mid,
                    'plusminus' => 1,
                    'permission' => $compare[$mid]['permission'],
                ];
            }
        }

        foreach (array_diff(array_keys($compare), array_keys($profileModules)) as $mid) {
            $parsed[] = [
                'admin_id' => $id,
                'module_id' => $mid,
                'plusminus' => 1,
                'permission' => $compare[$mid]['permission'],
            ];
        }

        if (!empty($parsed)) {
            DB::table('module_assignment')->insert($parsed);
        }

        return $this->response(200, 'Permission Successfully Customized');
    }
}
