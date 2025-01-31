<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;

use Validator;
use Auth;

class AdminController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->modulekey = 'admin';
        $this->moduleName = 'admins';
        $this->viewFolder = 'admins.admins';
        $this->apiController = 'App\Http\Controllers\Api\AdminController';
    }

    public function home(Request $request)
    {
        return view('admins.home');
    }

    public function changePassword(Request $request)
    {
        $actionRoute = route("api.{$this->moduleName}.change-password");
        $actionMethod = 'post';

        return view("{$this->viewFolder}.change-password", compact('actionRoute', 'actionMethod'));
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->only(['username', 'password']);

            $validation = Validator::make($data, [
                'username' => 'required',
                'password' => 'required',
            ]);

            if (Auth::guard('admin')->attempt($data)) {
                $admin = Auth::guard('admin')->user();
                $token = $admin->refreshToken();
                $request->session()->put('api_token', $token);
                return redirect()->route('admins.home');
            } else {
                return redirect()->back()
                    ->with('error', 'Invalid Username / Password');
            }
        }

        return view('admins.login');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('admins.login');
    }
}
