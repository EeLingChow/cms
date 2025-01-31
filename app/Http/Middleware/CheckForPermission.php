<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class CheckForPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $module, $permission = null)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin->permissionAllowed($module, $permission)) {
            abort(403, 'Permission Denied');
        }

        return $next($request);
    }
}
