<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Signature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next,$name='X-name')
    {
        $response= $next($request);
        return $response->header($name,config('app.name'));
    }
}
