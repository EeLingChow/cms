<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class ActionAuditLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $module, $action)
    {
        $admin = auth('admin')->user();

        $audit = new AuditLog;
        $audit->admin_id = $admin->id;
        $audit->action = $action;
        $audit->module = $module;
        $audit->ip = $request->ip();
        $audit->uri = $request->getRequestUri();
        $audit->postparams = json_encode($request->all());
        $audit->save();

        return $next($request);
    }
}
