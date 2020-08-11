<?php

namespace App\Http\Middleware;

use Closure;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->role == 'owner'){
            return redirect('fail');
        } elseif ($request->role == 'manager'){
            return redirect('fail');
        }
        
        return $next($request);
    }
}
