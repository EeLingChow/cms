<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use Validator;

class ApiModel extends Model
{
    protected $isUpdate = false;
    protected $guard = null;
    protected $appuser;
    protected $visibleData = [];
    protected $rules = [];
    protected $customMessages = [];
    protected $validationAttributes = [];

    protected $logLastUser = false;
    protected $lastUserField = 'username';

    public $sortMap = [];
    public $errors = [];
    public $routename = '';
    public $loggable = true;
    public $uploadable = false;


    public $joins = [];
    public $specialFilterFields = ['keyword'];

    public function __construct()
    {
        parent::__construct();
    }

    public function addRules($additionRules = [])
    {
        $this->rules = array_merge($this->rules, $additionRules);
    }

    public function authguard()
    {
        return $this->guard;
    }

    public function visibleData()
    {
        return $this->visibleData;
    }

    public function isSpecialFilterField($field)
    {
        return in_array($field, $this->specialFilterFields);
    }

    public function applyFilter($builder, $field, $v)
    {
        //do nth
    }

    public function applyPostFilter($results, $filters)
    {
        return $results;
    }

    public function isUpdate($isUpdate = true)
    {
        $this->isUpdate = $isUpdate;
    }

    public function fillFromRequest(Request $request, $data = null)
    {
        if (!$data) {
            $data = array_filter($request->all(), 'strlen');
        }

        $this->fill($data);
    }

    public function getQuery()
    {
        return $this->query();
    }

    public function validate($data, $id = null, $intersectOnly = false, $custom = null)
    {
        $idOnly = $id == null ? 'NULL' : $id;


        $rules = !empty($custom) ? $custom : $this->rules;

        $filtered = [];
        foreach ($rules as $field => $rule) {
            $filtered[$field] = str_replace('[id]', $idOnly, $rule);
        }

        if ($intersectOnly) {
            $filtered = array_intersect_key($filtered, $data);
        }

        $v = Validator::make($data, $filtered, $this->customMessages);
        $v->setAttributeNames($this->validationAttributes);

        if ($v->fails()) {
            $this->errors = $v->errors()->getMessages();
            return false;
        }

        return true;
    }

    public function apply($appuser, $builder, $custom = [])
    {
        //do nth
    }

    public function setAppUser($appuser)
    {
        $this->appuser = $appuser;
    }

    public function parseFormValue($attribute)
    {
        return $this->$attribute;
    }

    public function applySave($appuser)
    {
        $this->setAppUser($appuser);
        if ($this->logLastUser) {
            $this->updated_by = $appuser->{$this->lastUserField};
        }
    }

    public function afterSave(Request $request)
    {
        //do nth
    }
}
