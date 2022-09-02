<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class BearerToken
{

    public function handle(Request $request, Closure $next)
    {

        $api_token = $request->bearerToken();

        if($api_token != 'testtoken')
        {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return $next($request);
    }
}
