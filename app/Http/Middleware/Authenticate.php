<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests;
use Illuminate\Http\Request;

class Authenticate implements AuthenticatesRequests
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle(Request $request, Closure $next)
    {
//        dd(config('auth.upload_key'));

        if ( $request->header('X-Upload-Key') !== config('auth.upload_key') ) {
            throw new AuthenticationException();
        }

        return $next($request);
    }

}
