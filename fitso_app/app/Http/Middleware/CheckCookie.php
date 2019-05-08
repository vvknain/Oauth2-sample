<?php

namespace App\Http\Middleware;

use Closure;

class CheckCookie
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
        $current_url = url()->current();

        if ($current_url != 'http://localhost:8000/login' && $current_url != 'http://localhost:8000/callback'){
            if(!($request->cookie('access_token'))){
                return redirect()->action('ListController@login');
            }
        }
        
        return $next($request);
    }
}
