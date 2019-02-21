<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$permission)
    {
        $able = false;
        foreach($permission as $perm){
            if ($request->user()->can($perm)) {
                $able = true;
            }
        }
        if($able == false)
            abort(403);
        return $next($request);
    }
}
