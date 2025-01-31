<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckForModule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $module)
    {
        $admin = auth('admin')->user();

        if (!$admin->permissionAllowed($module)) {
            abort(403, 'Permission Denied');
        }

        return $next($request);
    }
}
