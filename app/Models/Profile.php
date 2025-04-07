<?php

namespace App\Models;

use App\Models\ApiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use DB;

class Profile extends ApiModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'profile';

    //default value
    protected $attributes = [
        'is_superadmin' => false,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'is_superadmin'
    ];

    protected $logLastUser = true;

    public function __construct()
    {
        parent::__construct();

        $this->routename = 'profiles';
        $this->rules = [
            'name' => 'required|max:255',
        ];
    }

    public function modules()
    {
        return $this->belongsToMany('App\Models\Module', 'profile_module_assignment', 'profile_id', 'module_id')
            ->withPivot('permission');
    }

    public function admins()
    {
        return $this->hasMany('App\Models\Admin');
    }

    public function getChoices($appuser)
    {
        $parsed = [];

        if ($appuser->is_superadmin) {
            $profiles = $this->all();
        } else {
            $profiles = $this->where('is_superadmin', false)->get();
        }


        foreach ($profiles as $p) {
            $parsed[$p->id] = $p->name;
        }

        return $parsed;
    }

    public function fillFromRequest(Request $request, $data = null)
    {
        if (!$data) {
            $data = array_filter($request->all(), 'strlen');
        }

        $checkboxes = ['is_superadmin'];

        foreach ($checkboxes as $field) {
            if (isset($data[$field]) && $data[$field] == 'on') {
                $data[$field] = true;
            } else {
                $data[$field] = false;
            }
        }

        $this->fill($data);
    }

    public function afterSave(Request $request)
    {
        if ($this->id) {
            DB::table('profile_module_assignment')
                ->where('profile_id', $this->id)
                ->delete();

            $data = [];
            $postdata = $request->all();

            if (isset($postdata['modules']) && !empty($postdata['modules'])) {
                foreach ($postdata['modules'] as $mid) {
                    $data[] = [
                        'profile_id' => $this->id,
                        'module_id' => $mid,
                        'permission' => isset($postdata['permissions'][$mid]) ? array_sum($postdata['permissions'][$mid]) : 0,
                    ];
                }
            }

            if (!empty($data)) {
                DB::table('profile_module_assignment')->insert($data);
            }
        }
    }
}
