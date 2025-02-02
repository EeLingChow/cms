<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;

class AuditLogController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->modulekey = 'auditLog';
        $this->moduleName = 'audit-logs';
        $this->viewFolder = 'admins.audit-logs';
        $this->apiController = 'App\Http\Controllers\Api\AuditLogController';
    }
}
