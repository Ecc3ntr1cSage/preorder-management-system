<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $roleId = auth()->user()->role_id;

        if ($roleId == 0 || $roleId == 1) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
