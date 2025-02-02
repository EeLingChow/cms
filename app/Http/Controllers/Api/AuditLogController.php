<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestfulController;
use Illuminate\Http\Request;

class AuditLogController extends RestfulController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = 'App\Models\AuditLog';
        $this->resource = 'App\Resources\AuditLog';
        $this->modulekey = 'auditLog';
        $this->moduleName = 'audit-logs';
    }
}
