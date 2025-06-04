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
use Illuminate\Http\Request;

class Admin extends ApiModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use HasFactory, Notifiable, SoftDeletes, Authenticatable, Authorizable, CanResetPassword;

    protected $table = 'admin';

    //default value
    protected $attributes = [
        'is_superadmin' => false,
    ];

    protected $fillable = [
        'profile_id',
        'username',
        'fullname',
        'is_superadmin',
    ];

    protected $hidden = [
        'password',
    ];


    public function __construct()
    {
        parent::__construct();

        $this->routename = 'admins';
        $this->rules = [
            'profile_id' => 'required',
            'username' => 'required|unique:admin,username,[id],id|max:50|alpha_num',
            'password' => 'required|min:6|max:255',
            'fullname' => 'required|max:255',
        ];
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = app('hash')->make($value);
    }

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile');
    }

    public function modules()
    {
        return $this->belongsToMany('App\Models\Module', 'module_assignment', 'admin_id', 'module_id')
            ->withPivot('plusminus', 'permission');
    }

    public function repName()
    {
        return substr($this->fullname, 0, 1);
    }

    // public function refreshToken()
    // {
    //     if ($this->id) {
    //         $token = Str::random(60);

    //         $this->api_token = hash('sha256', $token);
    //         $this->save();
    //         return $token;
    //     }

    //     return null;
    // }

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

        $return = [];

        $profile = $this->profile;
        foreach ($profile->modules as $m) {
            $return[$m->$returnKey] = $m->pivot->permission;
        }

        foreach ($this->modules as $m) {
            if ($m->pivot->plusminus == 1) {
                $return[$m->$returnKey] = $m->pivot->permission;
            } else if ($m->plusminus == -1) {
                unset($return[$m->$returnKey]);
            }
        }

        return $this->cachedModules = $return;
    }

    public function getMenuLinks($refresh = false)
    {
        if ($refresh == false && !empty($this->cachedMenuLinks)) {
            return $this->cachedMenuLinks;
        }

        $data = [];
        $keys = $this->allowedModules();

        if (!empty($keys)) {
            $query = Module::query();

            $modules = $query->where('is_hidden', false)
                ->where(function ($query) use ($keys) {
                    $query->orWhere('is_master', true)
                        ->orWhereIn('modulekey', array_keys($keys));
                })
                ->orderBy('is_master', 'desc')
                ->orderBy('sequence', 'asc')
                ->get();

            foreach ($modules as $m) {
                $masterId = $m->master_id;

                if (!array_key_exists($masterId, $data)) {
                    $data[$masterId] = [
                        'submodules' => [],
                        'routes' => [],
                    ];
                }

                $parsed = [
                    'name' => $m->name,
                    'icon' => $m->icon,
                    'route' => $m->route,
                ];

                try {

                    if (!empty($m->route)) {
                        $r = route($m->route);
                    }

                    if (!$m->is_master) {
                        $data[$masterId]['submodules'][] = $parsed;

                        if (!empty($m->route)) {
                            $data[$masterId]['routes'][] = $m->route;
                        }
                    } else {
                        $data[$masterId]['master'] = $parsed;
                    }
                } catch (\Exception $e) {
                }
            }
        }

        $return = [];
        foreach ($data as $masterId => $module) {
            if (!empty($module['submodules'])) {
                $return[$masterId] = $module;
            }
        }

        return $this->cachedMenuLinks = $return;
    }

    public function fillFromRequest(Request $request, $data = null)
    {
        if (!$data) {
            $data = array_filter($request->all(), 'strlen');
        }

        if (isset($data['password'])) {
            $this->password = $data['password'];
        }

        $this->fill($data);
    }
}
