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
}
