<?php

namespace App\Models;

use App\Models\ApiModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Admin extends ApiModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use HasFactory, Notifiable, SoftDeletes, Authenticatable, Authorizable, CanResetPassword;

    protected $table = 'admin';

    protected $fillable = [
        'username',
        'fullname',
        'role',
    ];

    protected $hidden = [
        'password',
        'api_token',
    ];


    public function __construct()
    {
        parent::__construct();

        $this->routename = 'admins';
        $this->rules = [
            'username' => 'required|unique:admin,username,[id],id|max:50|alpha_num',
            'password' => 'required|min:6|max:255',
            'fullname' => 'required|max:255',
            'role' => 'required|max:50',
        ];
    }

    public function repName()
    {
        return substr($this->fullname, 0, 1);
    }

    public function refreshToken()
    {
        if ($this->id) {
            $token = Str::random(60);

            $this->api_token = hash('sha256', $token);
            $this->save();
            return $token;
        }

        return null;
    }

    public function permissionAllowed($modulekey, $permission = null)
    {
        $modules = $this->allowedModules();
        $permissionCases = ['delete', 'update', 'create', 'read'];
        $hasModule = in_array($modulekey, array_keys($modules));


        if ($permission == null || !in_array($permission, $permissionCases) && !$hasModule) {
            //check module key only
            return $hasModule;
        }

        $binary = str_pad(decbin($modules[$modulekey]) . '', 4, '0', STR_PAD_LEFT);
        $key = array_search($permission, $permissionCases);

        return $binary[$key] == '1';
    }

    public function allowedModules($returnKey = 'modulekey', $refresh = false)
    {
        if ($refresh == false && !empty($this->cachedModules)) {
            return $this->cachedModules;
        }

        $module_assignment = [
            'superadmin' => 'admin|floor|category',
            'admin' => 'admin|floor|category',
        ];

        $return = [];

        $modules = explode('|', $module_assignment[$this->role]);
        foreach ($modules as $m) {
            switch ($this->role) {
                case 'superadmin':
                    $return[$m] = 15;
                    break;
                case 'admin':
                    $return[$m] = 7;
                    break;
            }
        }

        return $this->cachedModules = $return;
    }

    public function getMenuLinks($refresh = false)
    {
        if ($refresh == false && !empty($this->cachedMenuLinks)) {
            return $this->cachedMenuLinks;
        }

        $data = [
            'admin' => [
                'name' => ['Admins'],
                'route' => ['admins.list'],
                'master' => 'Super Admin',
            ],
            'floor' => [
                'name' => ['Floors'],
                'route' => ['floors.list'],
                'master' => 'Floors',
            ],
            'category' => [
                'name' => ['Categories'],
                'route' => ['categories.list'],
                'master' => 'Categories',
            ],
        ];

        $return = [];
        $modules = $this->allowedModules();

        if (!empty($modules)) {
            foreach ($modules as $m => $permission) {

                for ($i = 0; $i < count($data[$m]['name']); $i++) {
                    $parsed = [
                        'name' => $data[$m]['name'][$i],
                        'route' => $data[$m]['route'][$i],
                    ];

                    $return[$data[$m]['master']]['submodules'][] = $parsed;
                    $return[$data[$m]['master']]['routes'][] = $data[$m]['route'][$i];
                }
            }
        }

        return $this->cachedMenuLinks = $return;
    }
}
