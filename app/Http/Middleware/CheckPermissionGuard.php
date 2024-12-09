<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermissionGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $permission, $guard = 'web')
    {
        $user = Auth::guard($guard)->user();

        if ($user && $user->can($permission, $guard)) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
