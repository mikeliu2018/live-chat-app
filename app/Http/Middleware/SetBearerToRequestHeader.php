<?php

namespace App\Http\Middleware;
use Closure;

class SetBearerToRequestHeader
{

    public function handle($request, Closure $next)
    {
        $request->headers->set('Authorization', 'Bearer ' . $request->get('token'));

        return $next($request);
    }
}
