<?php

namespace App\Models;

use App\Models\ApiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Module extends ApiModel
{
    const PERMISSION_READ = 1;
    const PERMISSION_WRITE = 2;
    const PERMISSION_UPDATE = 4;
    const PERMISSION_DELETE = 8;

    use HasFactory, SoftDeletes;

    protected $table = 'module';

    //default value
    protected $attributes = [
        'is_superadmin' => false,
        'is_hidden' => false,
        'is_master' => false,
        'sequence' => 0.0,
    ];

    protected $fillable = [
        'master_id',
        'name',
        'modulekey',
        'sequence',
        'icon',
        'route',
        'is_superadmin',
        'is_master',
        'is_hidden',
    ];

    protected $logLastUser = true;

    public function __construct()
    {
        parent::__construct();

        $this->routename = 'modules';
        $this->rules = [
            'master_id' => 'required|numeric',
            'name' => 'required|max:255',
            'modulekey' => 'required|unique:module,modulekey,[id],id|max:100',
            'sequence' => 'required|numeric',
            'icon' => 'max:100',
            'route' => 'max:100',
        ];
    }

    public function getMasterChoices($includeHidden = false)
    {
        $parsed = [];
        $query = $this->where('is_master', true);

        if (!$includeHidden) {
            $query->where('is_hidden', false);
        }

        $masters = $query->get();

        foreach ($masters as $m) {
            $parsed[$m->id] = $m->name;
        }

        asort($parsed);
        return $parsed;
    }

    public function apply($appuser, $builder, $custom = [])
    {
        if (!$appuser->is_superadmin) {
            $builder->where('is_superadmin', false);
        }
    }

    public function fillFromRequest(Request $request, $data = null)
    {
        if (!$data) {
            $data = array_filter($request->all(), 'strlen');
        }

        $checkboxes = ['is_master', 'is_hidden', 'is_superadmin'];

        foreach ($checkboxes as $field) {
            if (isset($data[$field]) && $data[$field] == 'on') {
                $data[$field] = true;
            } else {
                $data[$field] = false;
            }
        }

        $this->fill($data);
    }

    public function getModuleList()
    {
        $masters = $this->getMasterChoices(true);

        $query = $this->where('is_master', false)
            ->orderBy('id', 'asc');

        if (!$this->appuser->is_superadmin) {
            $query->where('is_superadmin', false);
        }

        $submodules = $query->get();

        $parsed = [];

        foreach ($submodules as $m) {
            if (!array_key_exists($m->master_id, $parsed)) {
                $parsed[$m->master_id] = [
                    'id' => $m->master_id,
                    'module' => $masters[$m->master_id],
                    'data' => [],
                ];
            }

            $parsed[$m->master_id]['data'][$m->id] = [
                'id' => $m->id,
                'module' => $m->name,
            ];
        }

        return $parsed;
    }
}
